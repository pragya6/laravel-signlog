function submitForm() {
  var form = document.getElementById("submitForm");
  let email = document.forms["submitForm"]["email"].value;
  let password = document.forms["submitForm"]["password"].value;
  let regxp = /^[A-Za-z]\w{7,14}$/;
  let regxe = /^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/; 

  form.addEventListener("submit", submitted());

  function submitted() {
      if(email=== "" || !regxe.test(email)){
        alert("Please! Enter a valid email");
      }
      else if(password=== "" || !regxp.test(password) || !password.length < 6 ){
        alert("Please! Enter a valid password");
      }
  }
}

