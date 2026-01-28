<?php

namespace Classes;

class Validator
{
    public static function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function validateUsername($username)
    {
        return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username);
    }

    public static function validatePassword($password)
    {
        return strlen($password) >= 6;
    }

    public static function sanitizeInput($input)
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    public static function validateContactForm($name, $email, $subject, $message)
    {
        $errors = [];

        if (empty($name) || strlen($name) < 2) {
            $errors[] = 'Name is required and must be at least 2 characters';
        }

        if (!self::validateEmail($email)) {
            $errors[] = 'Valid email is required';
        }

        if (empty($subject) || strlen($subject) < 3) {
            $errors[] = 'Subject is required and must be at least 3 characters';
        }

        if (empty($message) || strlen($message) < 10) {
            $errors[] = 'Message is required and must be at least 10 characters';
        }

        return $errors;
    }

    public static function validateProductForm($name, $description, $price)
    {
        $errors = [];

        if (empty($name) || strlen($name) < 3) {
            $errors[] = 'Product name is required';
        }

        if (empty($description) || strlen($description) < 10) {
            $errors[] = 'Description must be at least 10 characters';
        }

        if (!is_numeric($price) || $price < 0) {
            $errors[] = 'Valid price is required';
        }

        return $errors;
    }

    public static function validateNewsForm($title, $content)
    {
        $errors = [];

        if (empty($title) || strlen($title) < 5) {
            $errors[] = 'Title is required and must be at least 5 characters';
        }

        if (empty($content) || strlen($content) < 20) {
            $errors[] = 'Content must be at least 20 characters';
        }

        return $errors;
    }
}
