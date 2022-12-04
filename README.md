# How to make your entity non-anemic, and keep the consistency of the model

I remember when I started my way with Tactical Design, everywhere I heard that we have to make non-anemic entities. By anemic entities I mean entities as a data structure, with plenty of setters and no logic.

But, that was tough to find out a solution, to how to make the entity non-anemic. 

In this article, I want to show you, how you can change your thinking about entities and make them non-anemic. 

First I want to show you the leaking model, I'm sure you've seen it in many projects

## A leaking model

Let's consider the example of a leaking model. 

As I mentioned before we need it anemic entity. Here we have, a simple User entity with two parameters `phoneNumber` and `phoneNumberAreaCode`. Area Code is nothing more than the prefix of the number, for example, +48, and the `phoneNumber` is the rest. 

So having said that, we have the whole phone number with code and number - +48 xxx xxx xxx (Format and number are depending on your country of course). 

```php 
class User
{
    private ?string $phoneNumber = null;
    private ?string $phoneNumberAreaCode = null;

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function setPhoneNumberAreaCode(string $phoneNumberAreaCode): void
    {
        $this->phoneNumberAreaCode = $phoneNumberAreaCode;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function getPhoneNumberAreaCode(): ?string
    {
        return $this->phoneNumberAreaCode;
    }
}
```

As you can guess, those two values shouldn't be persisted separately. 

Yep, so what we have to do next? Maybe a service? Yes! A service will be perfect element of something which can set the number and prefix. Like an orchestra conductor! 

Here is a service: 
```php
class UpdatePhoneNumberService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    /**
     * @throws UpdatePhoneNumberException
     */
    public function update(string $phoneNumber, string $areaCode, string $userId): void
    {

        // format the numbers 

        // check if the parameters are not empty

        // tens of lines...

        // check if the number isn't assigned to any other user

        // again... tens of lines...
            
        $user = $this->userRepository->findById($userId);

        // finally you can make a set 
        $user->setPhoneNumber($phoneNumber);
        $user->setPhoneNumberAreaCode($areaCode);

        $this->userRepository->save($user);
    }
}
```

The code is good enough until another dev won't get into the tens of code of your service. And just figure out a better solution.

Make a hacking, if we have User entity with public API (`setPhoneNumber` and `setPhoneNumberAreaCode`). 

```php 
class PhoneController
{
    ...

    public function updateAction(
        Request $request,
        User $user, // logged user
    ) {
        ...
        $user->setPhoneNumber(...);
        $this->userRepository->save($user);
        ...
    }

``` 

## Oh no! The Dev forgot about an area code!

What's happened here? The new dev forgot about an area code.
That is a leaking model! If you have a few parameters that shouldn't be changed separately, the entity has to check the consistency.

One source of truth should be in the entity.

## Ok but how can I change to make it happen? 

How we can move the logic to an entity if the entity isn't a service and we can't inject there any objects?

Let's use another building block, a policy. Take a look at the example of a refactored entity. 

```php 
class User
{
    private string $id;
    private PhoneNumber $phoneNumber;
    private bool $phoneNumberVerified = false;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function updatePhoneNumber(PhoneNumber $phoneNumber, ChangePhoneNumberPolicy $policy): Result
    {
        $result = $policy->canBeUpdated($this, $phoneNumber);

        if ($result->isFailure()) {
            return $result;
        }

        $this->phoneNumber = $phoneNumber;

        return Result::success(new PhoneNumberUpdatedEvent($this->getId(), $phoneNumber));
    }
```

As you can see now the entity has a method `updatePhoneNumber`, there are no `sets` methods. The `updatePhoneNumber` method takes a parameter `ChangePhoneNumberPolicy`. 

Also we can notice new builidng block a value object - `PhoneNumber`. So it forces a validation, we can't persist User entity without required parameter. 

Let's see the policy. 

```php 
class ChangePhoneNumberPolicy
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function canBeUpdated(User $user, PhoneNumber $phoneNumber): Result
    {
        $differentUser = $this->userRepository->findByPhoneNumber($phoneNumber);

        if ($differentUser instanceof User && !$differentUser->isEqual($user)) {
            return Result::failure(new Reason('the phone number is already in use'));
        }

        // ... other conditions ...

        // if the requires changed, you'll change only the policy, or make interface and you'll change your DI config

        return Result::success();
    }
}
```

Mostly policies will be behind the interface because the logic could change. Sometimes you'll see that policies are injected through a set method, and entities can be built by factories. 
Generally, we ended up with one place to change, your model will be consistent and validated. Now you know how to use policy.
