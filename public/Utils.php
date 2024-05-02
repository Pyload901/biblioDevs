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
    public static function isSuper(): bool {
        return (
            isset($_SESSION["user_role"])
            && ($_SESSION["user_role"] == Role::SUPER->value)
        );
    }
    public static function emailChecker($email): bool {
        $res = true;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $res = false;
        return $res;
    }
    public static function passwordChecker($password): bool {
        $res = false;
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[\W]+@', $password);
        if ($uppercase && $lowercase && $number && $specialChars && strlen($password) > 8) {
            $res = true;
        }
        return $res;
    }
    public static function confirmPassword($password, $confirmPassword): bool {
        return $password === $confirmPassword;
    }
    public static function isInteger($exp): bool {
        return (!preg_match("/[^0-9]/",$exp));
    }
    public static function isbnChecker($isbn): bool {
        return (preg_match("@^(?=(?:\D*\d){10}(?:(?:\D*\d){3})?$)[\d-]+$@",$isbn));
    }
    public static function generateCSRFToken(): string {
        $token = md5(uniqid(mt_rand(), true));
        $_SESSION["csrf_token"] = $token;
        return $token;
    }
    public static function validateCSRFToken(): bool {
        return ($_POST["csrf_token"] === $_SESSION["csrf_token"]);
    }
    public static function isDir($string): bool {
        return preg_match('/\.\.?\/[^\n"?:*<>|]+\.[A-z0-9]+/', $string);
    }
    public static function getErrorsView(): string {
        return "./view/error/messages.phtml";
    }
}