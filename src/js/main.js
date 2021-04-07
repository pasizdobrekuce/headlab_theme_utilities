import documentationLinks from './functions/documentationLinks';
import selectPrettyprint from './functions/selectPrettyprint';
import 'code-prettify';

(function ($) {

    //Init code-prettify
    window.addEventListener('load', function (event) { PR.prettyPrint(); }, false);

    // Do the tabs
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (event) {
        event.target // newly activated tab
        event.relatedTarget // previous active tab
    });

    documentationLinks('.documentation-links', '.documentation-link');

    selectPrettyprint('pre.prettyprint');

})(jQuery);