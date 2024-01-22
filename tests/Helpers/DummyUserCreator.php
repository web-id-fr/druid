<?php

namespace Webid\Druid\Tests\Helpers;

use Webid\Druid\App\Models\Dummy\DummyUser;

trait DummyUserCreator
{
    private function createDummyUser($params = []): DummyUser
    {
        return DummyUser::factory($params)->create();
    }
}
