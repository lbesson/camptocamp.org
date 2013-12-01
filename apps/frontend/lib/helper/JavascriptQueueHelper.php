<?php

// FIXME : dirty trick
if (isset($sf_user))
{
    // we are in a template
    use_helper('Javascript');
}
else
{
    // we are in an action
    sfLoader::loadHelpers('Javascript');
}

/*
 * See web/static/js/queue.js
 * The helper wraps the js code so that it becomes the content
 * of a function that will be executed after jquery is loaded
 * In case of an ajax call, we assume that jquery is already loaded
 *
 * You can safely refer C2C global var
 */
function javascript_queue($js) {

  if (sfContext::getInstance()->getRequest()->isXmlHttpRequest())
  {
      return javascript_tag(Minify_UglifyJSCompiler::minify('(function(C2C){' . $js . '})(window.C2C=window.C2C||{});'));
  }
  else
  {
      return javascript_tag(Minify_UglifyJSCompiler::minify('(function(w,c){w[c]=w[c]||{};(w[c]._q=w[c]._q||[]).push(function(){'.
          $js."});})(window,'C2C');"));
  }
}
