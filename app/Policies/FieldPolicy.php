<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Field;

class FieldPolicy {

    public function update(User $user, Field $field) {
        return true;
        return $field->user_id === $user->id || $user->user_type == 'Admin' || $user->user_type == 'Supervisor';
    }

    public function delete(User $user, Field $field) {
        return ($field->user_id === $user->id) || $user->user_type == 'Admin' || $user->user_type == 'Supervisor';
    }

}
