<?php 

class  VideoDetailsFormProvider {
  
   // method to create uploadfrom

   public function createUploadForm() {
      $fileInput        = $this->createFileInput();
      $titleInput       = $this->createTitleInput();
      $descriptionInput = $this->createDescriptionInput();
      $privacyInput     = $this->createPrivacyInput();
      $categoryInput    = $this->createCategoryInput();
      $uploadButton     = $this->createUploadButton();
      return "<form action='processing.php' method='POST' enctype='multipart/form-data'>
               $fileInput
               $titleInput
               $descriptionInput
               $privacyInput
               $categoryInput
               $uploadButton
              </form>";
   }


   public function createEditDetailsForm($video) {

      $titleInput       = $this->createTitleInput($video->getTitle());
      $descriptionInput = $this->createDescriptionInput($video->getDescription());
      $privacyInput     = $this->createPrivacyInput($video->getPrivacy());
      $categoryInput    = $this->createCategoryInput($video->getCategory());
      $saveButton     = $this->createSaveButton();
      return "<form method='POST'>
               $titleInput
               $descriptionInput
               $privacyInput
               $categoryInput
               $saveButton
              </form>";
   }

   private function createFileInput() {
      return " <div class='form-group'>
                  <label for='fileInput'>حمل الفيديو الخاص بك</label>
                  <input type='file' name='fileInput' id='fileInput' required>
               </div>";
   }
   private function createTitleInput($value = null) {

      if($value == null) {
         $value = "";
      }
      return "<div class='form-group'>
                  <input type='text' class='form-control' name='titleInput' value='$value' id='titleInput' placeholder='عنوان الفيديو' required>
              </div>";
   }

   private function createDescriptionInput($value = null) {
      if($value == null) {
         $value = "";
      }
      return "<div class='form-group'>
                  <textarea name='descriptionInput' class='form-control' id='descriptionInput' rows='3' placeholder='وصف الفيديو' required>$value</textarea>
              </div>";
   }

   private function createPrivacyInput($value = null) {
      if($value == null) {
         $value = "";
      }

      $PrivateSelected = $value == 0 ? "selected" : "";
      $publicSelected = $value == 1 ? "selected" : "";
      return "<div class='form-group'>
               <select name='privacyInput' class='form-control' value='$value' id='privacyInput'>
                  <option $PrivateSelected value='0'>خاص </option>
                  <option $publicSelected value='1'>عام</option>
               </select>
            </div>";
   }

   private function createCategoryInput ($value = null) {
      global $con;

      if($value == null) {
         $value = "";
      }
      
    
      $query = $con->prepare("SELECT * FROM categories");
      $query->execute();
      $html = "<div class='form-group'>";

      $html .= "<select name='categoryInput' class='form-control' value='$value' id='categoryInput'>";

      while($row = $query->fetch(PDO::FETCH_ASSOC)) {
         $id = $row['id'];
         $name = $row['name'];

         $selected = ($value == $id) ? "selected" : "";

         $html .= "<option $selected value='$id'>$name</option>";
     }

      $html .= '</select>';

      $html .= "</div>";

      return $html;
   }

   private function createUploadButton() {
      return '<button type="submit" name="uploadButton" class="btn btn-primary">تحميل </button>';
   }

   private function createSaveButton() {
      return '<button type="submit" name="saveButton" class="btn btn-primary">حفظ</button>';
   }
}