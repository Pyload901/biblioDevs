<?php
enum Role: string {
    case SUPER = "SUPER";
    case ADMIN = "ADMIN";
    case USER = "USER";
}

class Utils {
    public static function isAdmin(): bool {
        return (isset($_SESSION["user_role"])
            && ($_SESSION["user_role"] == Role::ADMIN->value
            || $_SESSION["user_role"] == Role::SUPER->value)
        );
    }
    public static function isLogged(): bool {
        return (
            isset($_SESSION) 
            && !empty($_SESSION["user_id"])
        );
    }
    public static function isDevMode(): bool {
        return (
            isset($_ENV["DEV"])
        );
    }
}