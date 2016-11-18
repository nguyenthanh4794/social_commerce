/**
 * Created by Nguyen Thanh on 11/6/2016.
 */
en4.store = {

}
en4.store.cart = {
    addProductBox: function (id) {
        var obj = Object.toQueryString({product_id: id});
        var btn_add = $('product_add_cart_'+id);
        var tmp_content = btn_add.innerHTML;
        var myRequest = new Request({
            url: en4.core.baseUrl + 'social-commerce/my-cart/ajax-add-product',
            method: 'post',
            data: obj,
            onRequest: function(){
                btn_add.innerHTML = '<a>Loading...<i class="ynicon yn-loading-icon-1"></i></a>'
            },
            onSuccess: function(responseText){
                console.log(responseText);
                btn_add.innerHTML = tmp_content;
            },
            onFailure: function(){
                alert('Sorry, your request failed :(');
            }
        });
        myRequest.send();
    }
}