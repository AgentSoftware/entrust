<?php

use PHPUnit\Framework\TestCase;
use Zizaco\Entrust\Entrust;
use Mockery as m;

class EntrustTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function testHasRole()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $app = new stdClass();
        $entrust = m::mock('Zizaco\Entrust\Entrust[user]', [$app]);
        $user = m::mock('_mockedUser');

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        $entrust->shouldReceive('user')
            ->andReturn($user)
            ->twice()->ordered();

        $entrust->shouldReceive('user')
            ->andReturn(false)
            ->once()->ordered();

        $user->shouldReceive('hasRole')
            ->with('UserRole', false)
            ->andReturn(true)
            ->once();

        $user->shouldReceive('hasRole')
            ->with('NonUserRole', false)
            ->andReturn(false)
            ->once();

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertTrue($entrust->hasRole('UserRole'));
        $this->assertFalse($entrust->hasRole('NonUserRole'));
        $this->assertFalse($entrust->hasRole('AnyRole'));
    }

    public function testCan()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $app = new stdClass();
        $entrust = m::mock('Zizaco\Entrust\Entrust[user]', [$app]);
        $user = m::mock('_mockedUser');

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        $entrust->shouldReceive('user')
            ->andReturn($user)
            ->twice()->ordered();

        $entrust->shouldReceive('user')
            ->andReturn(false)
            ->once()->ordered();

        $user->shouldReceive('can')
            ->with('user_can', false)
            ->andReturn(true)
            ->once();

        $user->shouldReceive('can')
            ->with('user_cannot', false)
            ->andReturn(false)
            ->once();

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertTrue($entrust->can('user_can'));
        $this->assertFalse($entrust->can('user_cannot'));
        $this->assertFalse($entrust->can('any_permission'));
    }

    public function testUser()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $app = new stdClass();
        $app->auth = m::mock('Auth');
        $entrust = new Entrust($app);
        $user = m::mock('_mockedUser');

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        $app->auth->shouldReceive('user')
            ->andReturn($user)
            ->once();

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertSame($user, $entrust->user());
    }
}
