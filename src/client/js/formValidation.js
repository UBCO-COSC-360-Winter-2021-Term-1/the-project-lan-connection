
// Validation for login form
function validateLogin() {
    var cancel = false;
    var required = document.getElementsByClassName("required-l");
    for (var i=0; i<required.length; i++) {
        if (required.item(i).value == "") {
            required.item(i).classList.add("missing-field");
            cancel = true;
        }
    }
    if (cancel) {
        return false;
    }
}
function removeHighlightLogin() {
    var required = document.getElementsByClassName("missing-field");
    for (var i = 0; i<required.length; i++) {
        if (required.item(i).value !== "") {
            required.item(i).classList.remove("missing-field");
        }
    }
}

// Validation for signup form
function validateSignup() {
    console.log('signing up');
    var cancel = false;
    var required = document.getElementsByClassName("required-s");
    for (var i=0; i<required.length; i++) {
        // check if element is empty
        if (required.item(i).value == "") {
            required.item(i).classList.add("missing-field");
            cancel = true;
        }
        // get password
        if (required.item(i).id == 'password') {
            var pw = required.item(i);
            console.log("password is "+pw.value);
        }
        // get pasword-check
        if (required.item(i).id == 'password-check') {
            var pwc = required.item(i);
            console.log("password-check is "+pwc.value);
        }
    }
    // get passwords
    if (pw.value != pwc.value) {
        pwc.classList.add("missing-field");
        cancel = true;
    }
    // stop submission
    if (cancel == true) {
        return false;
    }
}
function removeHighlightSignup() {
    var required = document.getElementsByClassName("missing-field");
    for (var i = 0; i<required.length; i++) {
        if (required.item(i).value !== "") {
            required.item(i).classList.remove("missing-field");
        }
    }
}


// wait till window is loaded to load javaScript
// window.onload = function() {
//     // setup listener on submission of form
//     var form = document.getElementsByTagName('form');
//     form.addEventListener('submit', function(e) {
//         // get all inputs
//         var inputs = document.getElementsByTagName('input');
//         // loop through inputs 
//         for (var i=0; i<inputs.length; i++) {
//             var input = inputs.item(i);
//             if (input.value == null || input.value == "") {
//                 console.log("Input Empty");
//                 // stop submission of form
//                 e.preventDefault();
//                 // highlight field
//                 input.classList.add('missing-field');
//             }
//         }
//     });

//     // setup listener on fields for when input done
//     var inputs = document.getElementsByTagName('input');
//     for (var i=0; i<inputs.length; i++) {
//         var input = inputs.item(i);
//         input.addEventListener('input', function() {
//             console.log('Unhighlighting missing field');
//             this.classList.remove('highlight');
//         });
//     }
    
// }

