<?PHP

namespace Core\Implementation;

use Core\Providers\MySQLModel;

class User extends MySQLModel {
    protected $table = 'users';
}

// User::table('users')->select('id', 'name')->get();