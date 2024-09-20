# Roomies Fraudable

[![Latest Version on Packagist](https://img.shields.io/packagist/v/roomies/fraudable.svg?style=flat-square)](https://packagist.org/packages/roomies/fraudable)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/roomies-com/fraudable/test.yml?branch=main&label=tests&style=flat-square)](https://github.com/roomies-com/fraudable/actions?query=workflow%3Atest+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/roomies/fraudable.svg?style=flat-square)](https://packagist.org/packages/roomies/fraudable)

Roomies Fraudable provides an integration layer between your Laravel app and AWS Fraud Detector. It leverages events and allows you to dispatch/ingest events for training, and later to predict events using your trained models.

Fraudable provides an integration between your Laravel app and AWS Fraud Detector. It leverages Laravel events as a way to ingest data to train models, and then to make predictions about events once you have collected enough data. It provides controls to update events as legitmate or fraudulent to improve your models, and to use prediction data to make assessments in your app.

Note that this is not a plug-and-play solution to detecting fraud. At the very least you will need to configure events, models and detectors in AWS Fraud Detector to get started.


## Installation

You can install the package via Composer:

```bash
composer require roomies/fraudable
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag=fraudable
```

Read through the config file to understand the AWS Fraud Detector integration and run the provided migration.

```bash
php artisan migrate
```

## Getting started

First add `Fraudable` to the model(s) that will be your "entities" inside AWS Fraud Detector. By default we'll snake-case the full class name to be the "entity" name - `App\Models\User` will become `app_models_user`, as AWS Fraud Detector only allows lowercase letters and underscores.

```php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Roomies\Fraudable\Concerns\Fraudable;

class User extends Authenticatable
{
    use Fraudable
}
```

Next, create events that will map to events inside AWS Fraud Detector. You can use regular Laravel extends and implement the `FraudableEvent` contract. It should return an instance of `PendingEvent` which will include the AWS Fraud Detector event name and the variables to be passed to the event. An instance of the current `Illuminate\Http\Request` will be provided to use for event variables.

```php
namespace App\Events\Users;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Roomies\Fraudable\Contracts\FraudableEvent;
use Roomies\Fraudable\PendingEvent;

class UserCreated implements FraudableEvent
{
    /**
     * Create a new event instance.
     */
    public function __construct(public User $user)
    {
        //
    }

    /**
     * Get the fraud event representation of the event.
     */
    public function toFraudEvent(Request $request): PendingEvent
    {
        $name = Str::of($this::class)->replace('\\', '')->snake()->toString();

        return new PendingEvent(name: $name, variables: [
            'email' => $this->user->email,
            'user_agent' => $request->userAgent(),
        ]);
    }
}
```

In this example we also snake-case the full class name, so `App\Events\Users\UserCreated` becomes `app_events_users_user_created` inside AWS Fraud Detector.

Finally, you can now begin to ingest events for training or prediction.

```php
$user = Auth::user();

$event = new App\Events\Users\UserCreated($user);

// Simply record the event to the database - an instance of `Roomies\Fraudable\Models\FraudEvent` is returned.
$fraudEvent = $user->ingest($event);

// Immediately upload the event for training.
$user->ingest($event)->upload();

// Get a prediction for the event
$predication = $user->ingest($event)->predict('detectorId');
```

When you make determinations about an entity you can retroactively update the recorded events.

```php
use Roomies\Fraudable\Label;

$user = Auth::user();

$user->relabel(Label::Fraudulent);
```