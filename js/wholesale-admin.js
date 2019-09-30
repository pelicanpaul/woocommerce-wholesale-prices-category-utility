var ws_admin_utility = {
    getParameterByName: function (name) {
        name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
        var regexS = "[\\?&]" + name + "=([^&#]*)";
        var regex = new RegExp(regexS);
        var results = regex.exec(window.location.search);
        if (results == null)
            return "";
        else
            return decodeURIComponent(results[1].replace(/\+/g, " "));
    }


};

jQuery(document).ready(function ($) {

    function allStorage() {

        var archive = {}, // Notice change here
            keys = Object.keys(localStorage),
            i = keys.length;

        while ( i-- ) {
            archive[ keys[i] ] = localStorage.getItem( keys[i] );
        }

        return archive;
    }


    $('#get-category-ids').submit(function (e) {
        e.preventDefault();

        var indicator = $('.ws-indicator');

        indicator.show();

         var cat = $(this).find('#cat');
         var catVal = cat.val();

        jQuery.ajax({
            type: 'POST',
            url: '/wp-admin/admin-ajax.php',
            data: {
                action: 'ajax_ajaxhandler',
                id: catVal
            },
            success: function (data) {
                indicator.hide();
                var data_catids  = data.trim();
                data_catids = data_catids.replace(/'/g,'"');

                var  data_catids_clean = data_catids.substring(0, data_catids.length-2);

                $('#category_ids').val(data_catids_clean);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                indicator.hide();
                console.log("Error");
            }
        });

    });



    $('#update-categories').submit(function (e) {

        e.preventDefault();

        var category_ids = $('#category_ids');

        var category_ids_val = category_ids.val();

        localStorage.setItem("category_ids", category_ids_val);

        var role_id = '';
        var role_val = '';

        $( '.discount-amount' ).each(function( index ) {

            role_id = $(this).attr('id');
            role_val = $(this).val();

            localStorage.setItem(role_id, role_val);
        });


        var idsArray = category_ids_val.split(",");

        var wp_edit = '/wp-admin/term.php?taxonomy=product_cat&tag_ID=' + idsArray[0] + '&post_type=product&gc_update=1';

        setTimeout(function () {
            window.location.href = wp_edit;
        }, 600);

    });

    // for processing the script

    productCat = $('body.taxonomy-product_cat');
    gcUpdate = ws_admin_utility.getParameterByName('gc_update');
    gcNext = ws_admin_utility.getParameterByName('gc_next');
    tagId = ws_admin_utility.getParameterByName('tag_ID');

    if (productCat.length && gcUpdate == 1) {

        var ids = localStorage.getItem("category_ids");
        var success = $('.notice.notice-success');

        var ls = allStorage();

        setTimeout(function () {

            Object.keys(ls).forEach(function(key) {
                if(key.indexOf('wholesale_discount') > 0){
                    console.log(key, ls[key]);
                    $('.' + key).val(ls[key]);
                }

            });

        }, 300);


        var idsArray = ids.split(",");
        var tagIdPos = idsArray.indexOf(tagId);
        var nextIdPos = tagIdPos + 1;
        var nextId = idsArray[nextIdPos];
        var arrayLength = idsArray.length;


        if (success.length) {

            setTimeout(function () {
                var wp_edit = '/wp-admin/term.php?taxonomy=product_cat&tag_ID=' + nextId + '&post_type=product&gc_update=1&gc_next=1';

                if (typeof(nextId) != "undefined") {
                    window.location.href = wp_edit;      // go to next page
                } else {
                    $('h1').text('Categories updated with Wholesale Pricing');

                    // cleanup

                    localStorage.removeItem("category_ids");

                    Object.keys(ls).forEach(function(key) {
                        if(key.indexOf('wholesale_discount') > 0){

                            localStorage.removeItem(key);

                        }

                    });
                }

            }, 1000);

        } else if (gcNext == 1) { // load next page

            setTimeout(function () {
                $('#edittag').submit();

            }, 1000);

        } else {

            setTimeout(function () {
                $('#edittag').submit();

            }, 1000);

        }


    }


});