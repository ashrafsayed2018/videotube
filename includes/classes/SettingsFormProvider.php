<?php 

class SettingsFormProvider {

    // method to create save the user data

   public function createUserDetailsForm($firstname,$lastname,$email) {
    $firstNameInput        = $this->createFirstNameInput($firstname);
    $lastNameInput         = $this->createLastNameInput($lastname);
    $emailInput            = $this->createEmailInput($email);
    $saveInput             = $this->createSaveUserDetails();

   
    return "<form action='settings.php' method='POST'>
                <span class='title'>بيانات المستخدم </span>
                $firstNameInput  
                $lastNameInput
                $emailInput 
                $saveInput
            </form>";
 }

 private function createFirstNameInput($value = null) {
     $value = ($value != null) ? $value : "";
    return " <div class='form-group'>
                <input type='text' name='firstaName' class='form-control' value='$value' placeholder='first name' id='firstname' >
             </div>";
 }
 private function createLastNameInput($value = null) {
    $value = ($value != null) ? $value : "";
    return "<div class='form-group'>
                <input type='text' name='lastName' class='form-control'  value='$value' placeholder='last name' id='lastname' >
            </div>";
 }

 private function createEmailInput($value = null) {
    $value = ($value != null) ? $value : "";

    return "<div class='form-group'>
                   <input type='email' name='email' class='form-control'  value='$value' placeholder='type your email' id='email' >

            </div>";
 }


 private function createSaveUserDetails() {
    return '<button type="submit" name="saveDetailsButton" class="btn btn-primary">حفظ</button>';
 }

 
 // change password method 
 public function createPasswordForm() {
    $oldPasswordInput        = $this->createPasswordInput("oldPassword","الرقم السري الحالى");
    $newPasswordInput        = $this->createPasswordInput("newPassword","الرقم السري الجديد");
    $newPassword2Input       = $this->createPasswordInput("newPassword2","تأكيد الرقم السري الجديد");
    $saveInput               = $this->createPasswordButton();

   
    return "<form action='settings.php' method='POST'>
                <span class='title'> Update Password</span>
                $oldPasswordInput  
                $newPasswordInput
                $newPassword2Input 
                $saveInput
            </form>";
 }

 private function createPasswordInput($name,$plceholder) {

    return "<div class='form-group'>
                   <input type='password' name='$name' class='form-control' placeholder='$plceholder' id='$name' >

            </div>";
 }

 private function createPasswordButton() {
    return '<button type="submit" name="UpdatePassword" class="btn btn-primary">تحديث</button>';
 }


}