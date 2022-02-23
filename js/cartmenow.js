var cartmenow_base="";
document.addEventListener("DOMContentLoaded", function(event) {
  //var container=document.getElementById("body");
  var body=document.body;

  var cart=document.createElement("div");
  cart.setAttribute("type","button");
  cart.setAttribute("value","cart");
  cart.setAttribute("class","cartmenow-cart-btn");
  cart.setAttribute("id","cartmenow-cart-btn");
  cart.setAttribute("onclick", "showCart()");
  body.append(cart);

  var cartcontent = document.createElement("div"); //input element, text
  cartcontent.setAttribute("class","cartmenow-modal");
  cartcontent.setAttribute("id","cartmenow-modal");
  body.append(cartcontent);

  var cartcontentmodalcontrol = document.createElement("div"); //input element, text
  cartcontentmodalcontrol.setAttribute("class","cartmenow-modal-control");
  cartcontentmodalcontrol.setAttribute("id","cartmenow-modal-control");
  cartcontent.append(cartcontentmodalcontrol);
  var close = document.createElement("span"); //input element, text
  close.setAttribute("class","cartmenow-close");
  close.innerHTML="&times;";
  close.setAttribute("onclick", "showCart()");
  cartcontentmodalcontrol.append(close);

  var cartcontentmodal = document.createElement("div"); //input element, text
  cartcontentmodal.setAttribute("class","cartmenow-modal-content");
  cartcontentmodal.setAttribute("id","cartmenow-modal-content");
  cartcontentmodal.innerHTML='<p>Some text in the Modal..</p>';
  cartcontentmodalcontrol.append(cartcontentmodal);


  var f = document.createElement("form");
  f.setAttribute('method',"post");
  f.setAttribute('action',"submit.php");

  var i = document.createElement("input"); //input element, text
  i.setAttribute('type',"text");
  i.setAttribute('name',"username");

  var s = document.createElement("input"); //input element, Submit button
  s.setAttribute('type',"button");
  s.setAttribute('value',"Submit");
  s.setAttribute('onclick',"go();");

  f.appendChild(i);
  f.appendChild(s);

  body.append(f);
  init();
});

function showCart()
{
  var session=getCookie("cartmenow_session");
  var cart=document.getElementById("cartmenow-modal");
  var cartcontent=document.getElementById("cartmenow-modal-content");
  if(cart.style.display=="block")
  {
    cart.style.display="none";
  }
  else {
    cart.style.display="block";
    snd_req("session="+session,cartmenow_base+"/get-cart","cartmenow-modal-content");
  }
}

function addToCart(id)
{
  var session=getCookie("cartmenow_session");
  showCart();
  snd_req("id_product="+id+"&session="+session,cartmenow_base+"/add-to-cart","cartmenow-modal-content");
}
function increaseQty(id)
{
  var session=getCookie("cartmenow_session");
  snd_req("id_product="+id+"&session="+session,cartmenow_base+"/add-to-cart","cartmenow-modal-content");
}
function decreaseQty(id_element)
{
  var session=getCookie("cartmenow_session");
  snd_req("id_element="+id_element+"&session="+session,cartmenow_base+"/decrease-qty","cartmenow-modal-content");
}

function showUserData()
{
  var session=getCookie("cartmenow_session");
  snd_req("session="+session,cartmenow_base+"/your-data","cartmenow-modal-content");
}

function showLogin()
{
  snd_req("",cartmenow_base+"/login","cartmenow-modal-content");
}

function showRegister()
{
  snd_req("",cartmenow_base+"/register","cartmenow-modal-content");
}

function showGuest()
{
  snd_req("",cartmenow_base+"/guest","cartmenow-modal-content");
}

function loginUser()
{
  var session=getCookie("cartmenow_session");
  var username=document.getElementById("cartmenow-input-email").value;
  var password=document.getElementById("cartmenow-input-password").value;
  var param="username="+username+"&password="+password+"&session="+session;
  snd_req(param,cartmenow_base+"/login-user","cartmenow-modal-content");
}

function registerUser()
{
  var email=document.getElementById("cartmenow-input-email").value;
  var password=document.getElementById("cartmenow-input-password").value;
  var password2=document.getElementById("cartmenow-input-password2").value;
  var param="email="+email+"&password="+password+"&password2="+password2;
  snd_req(param,cartmenow_base+"/register-user","cartmenow-modal-content");
}

function saveUserdata()
{
  var session=getCookie("cartmenow_session");
  var email=document.getElementById("cartmenow-input-email").value;
  var salutation=document.getElementById("cartmenow-select-salutation").value;
  var firstname=document.getElementById("cartmenow-input-firstname").value;
  var lastname=document.getElementById("cartmenow-input-lastname").value;
  var street=document.getElementById("cartmenow-input-street").value;
  var number=document.getElementById("cartmenow-input-number").value;
  var zip=document.getElementById("cartmenow-input-zip").value;
  var city=document.getElementById("cartmenow-input-city").value;
  var country=document.getElementById("cartmenow-select-country").value;
  var notes=document.getElementById("cartmenow-text-notes").value;
  var param="email="+email+"&salutation="+salutation+"&firstname="+firstname+"&lastname="+lastname+"&street="+street+"&number="+number+"&zip="+zip+"&city="+city+"&country="+country+"&notes="+notes+"&session="+session;
  snd_req(param,cartmenow_base+"/userdata","cartmenow-modal-content");
}

function setPayment(id,token)
{
  var session=getCookie("cartmenow_session");
  var param="id_payment="+id+"&token="+token+"&session="+session;
  snd_req(param,cartmenow_base+"/order-summary","cartmenow-modal-content");
}

function checkoutOrder()
 {
   loadScript("https://js.stripe.com/v3/",function(){});
   var session=getCookie("cartmenow_session");
   var token=document.getElementById("cartmenow-input-token").value;
   var param="token="+token+"&session="+session;
   snd_req(param,cartmenow_base+"/checkout","cartmenow-modal-content");
 }

function getSession()
{
  var param="";
  snd_req(param,cartmenow_base+"/getsession","cartmenow-modal-content");
}

function init()
{
  var getLocation = function(href) {
    var l = document.createElement("a");
    l.href = href;
    return l;
};
  var src=document.getElementById("cartmenow-js-file");
  var location=getLocation(src.src);
  cartmenow_base=location.protocol+"//"+location.hostname+location.pathname.substr(0,location.pathname.length-16);
  var products=document.querySelectorAll('[data-cartmenow]');
   products.forEach(function(product){
    product.setAttribute("onclick", "addToCart("+product.getAttribute("data-cartmenow")+")");
  });
  if(getCookie("cartmenow_session")=="")
  {
    getSession();
  }
}


var waiting_img="media/images/ajax-loader.svg";
function snd_req(Param,Url,Obj,WithLoader)
{
  var isActiveX = !!window.ActiveXObject,
    xhr = isActiveX ? new ActiveXObject("Microsoft.XMLHTTP"): new XMLHttpRequest();
    xhr.withCredentials = true;
    xhr.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200){
        // the request is complete, parse data and call callback
        var response = this.responseText;
        req_handler(response,Obj);
    }
};
    xhr.open("POST",Url,true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(Param);
}
function req_handler(Response,Obj)
{
	document.getElementById(Obj).innerHTML=Response;
  var script=document.getElementById("cartmenow-script");
  if(script!=null) eval(script.innerHTML);
}
function loadScript(url, callback){

    var script = document.createElement("script")
    script.type = "text/javascript";

    if (script.readyState){  //IE
        script.onreadystatechange = function(){
            if (script.readyState == "loaded" ||
                    script.readyState == "complete"){
                script.onreadystatechange = null;
                callback();
            }
        };
    } else {  //Others
        script.onload = function(){
            callback();
        };
    }

    script.src = url;
    document.getElementsByTagName("head")[0].appendChild(script);
}


function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays*24*60*60*1000));
  var expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}
