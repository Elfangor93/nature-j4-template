<?php 
  function modChrome_custom( $module, &$params, &$attribs ) 
  {
    echo '<div class="'.$params->get('moduleclass_sfx').'">';
      echo $module->content;
    echo '</div>';
  }
?>
