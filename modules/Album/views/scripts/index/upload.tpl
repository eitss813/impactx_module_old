<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Album
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: upload.tpl 9987 2013-03-20 00:58:10Z john $
 * @author     Sami
 */
?>

<script type="text/javascript">
  var updateTextFields = function()
  {
    var fieldToggleGroup = ['#title-wrapper', '#category_id-wrapper', '#description-wrapper', '#search-wrapper',
                            '#auth_view-wrapper',  '#auth_comment-wrapper', '#auth_tag-wrapper'];
        fieldToggleGroup = $$(fieldToggleGroup.join(','))
    if ($('album').get('value') == 0) {
      fieldToggleGroup.show();
    } else {
      fieldToggleGroup.hide();
    }
  }
  en4.core.runonce.add(function() {
    new Uploader('upload_file', {
      uploadLinkClass : 'buttonlink icon_photos_new',
      uploadLinkTitle : '<?php echo $this->translate("Add Photos");?>',
      uploadLinkDesc : '<?php echo $this->translate("Click \"Add Photos\" to select one or more photos from your computer."
        . " After you have selected the photos, they will begin to upload right away. "
        . "When your upload is finished, click the button below your photo list to save them to your album.");?>'
    });
    updateTextFields();
  });
</script>

<?php echo $this->form->render($this) ?>


<script type="text/javascript">
  $$('.core_main_album').getParent().addClass('active');
</script>
