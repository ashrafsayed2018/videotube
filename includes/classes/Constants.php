<?php 

class Constants{
    public static $firstNameCharacters = "الاسم الاول يجب ان يكون بين 2 و25 حرف";

    public static $lastNameCharacters = "الاسم الاخير يجب ان يكون بين 2 و25 حرف ";
    
    public static $usernameCharacters = "اسم المستخدم يجب ان يكون بين 2 و 25 حرف ";
    public static $usernameTaken = "اسم المستخدم موجود بالفعل ";

    public static $emailCharacters = "الايميل يجب ان يكون اكبر من 5 احرف  ";
    public static $emailsNotMatching = "الايملين غير متطابقين ";
    public static $emailTaken = "الايميل مستخدم بالفعل ";
    public static $emailInvalid = "ايميل غير صالح";

    public static $passwordCharacters = "الرقم السري يجب ان يكون بين 5 و 25 حرف ";
    public static $passwordsNotMatching = "الارقام السريه غير متطابقه";
    public static $passwordOnlyLettersAndDigits = "الرقم السري يجب ان يكون حروف وارقام";
    public static $passwordOldPassword          = "الرقم السري الحالي غير صحيح";

    public static $loginFailed = "اسم المستخدم او الرقم السري غير صحيح ";
}