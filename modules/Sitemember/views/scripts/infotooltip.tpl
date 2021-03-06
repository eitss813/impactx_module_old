<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitemember
 * @copyright  Copyright 2014-2015 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: infotooltip.tpl 2014-07-20 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php
$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/styles/style_infotooltip.css');
?>
<script type="text/javascript">

  var CommentLikesTooltips;
  en4.core.runonce.add(function() {
    // Add hover event to get tool-tip
    var feedToolTipAAFEnable = true;
    if (feedToolTipAAFEnable) {
      var show_tool_tip = false;
      var counter_req_pendding = 0;
      $$('.sea_add_tooltip_link').addEvent('mouseover', function(event) {
        var el = $(event.target);
        ItemTooltips.options.offset.y = el.offsetHeight;
        ItemTooltips.options.showDelay = 0;
        if (!el.hasAttribute("rel")) {
          el = el.parentNode;
        }
        show_tool_tip = true;
        if (!el.retrieve('tip-loaded', false)) {
          counter_req_pendding++;
          var resource = '';
          if (el.hasAttribute("rel"))
            resource = el.rel;
          if (resource == '')
            return;

          el.store('tip-loaded', true);
          el.store('tip:title', '<div class="" style="">' +
                  ' <div class="uiOverlay info_tip" style="width: 300px; top: 0px; ">' +
                  '<div class="info_tip_content_wrapper" ><div class="info_tip_content"><div class="info_tip_content_loader">' +
                  '<img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Seaocore/externals/images/core/loading.gif" alt="Loading" /><?php echo $this->translate("Loading ...") ?></div>' +
                  '</div></div></div></div>'
                  );
          el.store('tip:text', '');
          // Load the likes
          var url = '<?php echo $this->url(array('module' => 'seaocore', 'controller' => 'feed', 'action' => 'show-tooltip-info'), 'default', true) ?>';
          el.addEvent('mouseleave', function() {
            show_tool_tip = false;
          });

          var req = new Request.HTML({
            url: url,
            data: {
              format: 'html',
              'resource': resource
            },
            evalScripts: true,
            onSuccess: function(responseTree, responseElements, responseHTML, responseJavaScript) {
              el.store('tip:title', '');
              el.store('tip:text', responseHTML);
              ItemTooltips.options.showDelay = 0;
              ItemTooltips.elementEnter(event, el); // Force it to update the text 
              counter_req_pendding--;
              if (!show_tool_tip || counter_req_pendding > 0) {
                //ItemTooltips.hide(el);
                ItemTooltips.elementLeave(event, el);
              }
              var tipEl = ItemTooltips.toElement();
              tipEl.addEvents({
                'mouseenter': function() {
                  ItemTooltips.options.canHide = false;
                  ItemTooltips.show(el);
                },
                'mouseleave': function() {
                  ItemTooltips.options.canHide = true;
                  ItemTooltips.hide(el);
                }
              });
              Smoothbox.bind($$(".sea_add_tooltip_link_tips"));
            }
          });
          req.send();
        }
      });
      // Add tooltips
      var window_size = window.getSize()
      var ItemTooltips = new SEATips($$('.sea_add_tooltip_link'), {
        fixed: true,
        title: '',
        className: 'sea_add_tooltip_link_tips',
        hideDelay: 200,
        offset: {'x': 0, 'y': 0},
        windowPadding: {'x': 370, 'y': (window_size.y / 2)}
      });
    }
  });
  var sitetagcheckin_id = '<?php echo $this->sitetagcheckin_id; ?>';
</script>