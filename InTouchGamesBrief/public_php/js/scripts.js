// Function for API Key copy button
function copyToClipboard() {
    var temptext = document.createElement("textarea");
    temptext.value = document.getElementById("key").getAttribute("value");
    document.body.appendChild(temptext);
    temptext.select();
    document.execCommand("copy");
    document.body.removeChild(temptext);
}

function showInputs(dropdownname) {
    var dropdown = document.getElementById(dropdownname);
    console.log(dropdown.options[dropdown.selectedIndex].text);
    switch(dropdown.options[dropdown.selectedIndex].text) {
        case "<< Select >>":
            document.getElementById("formsubmit").style.display = "none";
            break;
        case "See all Users":            
            document.getElementById("editoptions").style.display = "none";
            document.getElementById("userid").style.display = "none";
            document.getElementById("useridlabel").style.display = "none";
            document.getElementById("firstname").style.display = "none";
            document.getElementById("firstnamelabel").style.display = "none";
            document.getElementById("surname").style.display = "none";
            document.getElementById("surnamelabel").style.display = "none";
            document.getElementById("dob").style.display = "none";
            document.getElementById("doblabel").style.display = "none";
            document.getElementById("phonenumber").style.display = "none";
            document.getElementById("phonenumberlabel").style.display = "none";
            document.getElementById("email").style.display = "none";
            document.getElementById("emaillabel").style.display = "none";
            document.getElementById("formsubmit").style.display = "block";


        break;
        case "Create User":
            document.getElementById("editoptions").style.display = "none";
            document.getElementById("userid").style.display = "none";
            document.getElementById("useridlabel").style.display = "none";
            document.getElementById("firstname").style.display = "block";
            document.getElementById("firstnamelabel").style.display = "block";
            document.getElementById("surname").style.display = "block";
            document.getElementById("surnamelabel").style.display = "block";
            document.getElementById("dob").style.display = "block";
            document.getElementById("doblabel").style.display = "block";
            document.getElementById("phonenumber").style.display = "block";
            document.getElementById("phonenumberlabel").style.display = "block";
            document.getElementById("email").style.display = "block";
            document.getElementById("emaillabel").style.display = "block";
            document.getElementById("formsubmit").style.display = "block";
        break;
        case "See User":
        case "Delete User":
            document.getElementById("editoptions").style.display = "none";
            document.getElementById("userid").style.display = "block";
            document.getElementById("useridlabel").style.display = "block";
            document.getElementById("firstname").style.display = "none";
            document.getElementById("firstnamelabel").style.display = "none";
            document.getElementById("surname").style.display = "none";
            document.getElementById("surnamelabel").style.display = "none";
            document.getElementById("dob").style.display = "none";
            document.getElementById("doblabel").style.display = "none";
            document.getElementById("phonenumber").style.display = "none";
            document.getElementById("phonenumberlabel").style.display = "none";
            document.getElementById("email").style.display = "none";
            document.getElementById("emaillabel").style.display = "none";
            document.getElementById("formsubmit").style.display = "block";
        break;
        case "Edit User":
            document.getElementById("editoptions").style.display = "block";
            document.getElementById("userid").style.display = "block";
            document.getElementById("useridlabel").style.display = "block";
            document.getElementById("firstname").style.display = "block";
            document.getElementById("firstnamelabel").style.display = "block";
            document.getElementById("surname").style.display = "none";
            document.getElementById("surnamelabel").style.display = "none";
            document.getElementById("dob").style.display = "none";
            document.getElementById("doblabel").style.display = "none";
            document.getElementById("phonenumber").style.display = "none";
            document.getElementById("phonenumberlabel").style.display = "none";
            document.getElementById("email").style.display = "none";
            document.getElementById("emaillabel").style.display = "none";
            document.getElementById("formsubmit").style.display = "block";
        break;
        case "First Name":
            document.getElementById("firstname").style.display = "block";
            document.getElementById("firstnamelabel").style.display = "block";
            document.getElementById("surname").style.display = "none";
            document.getElementById("surnamelabel").style.display = "none";
            document.getElementById("dob").style.display = "none";
            document.getElementById("doblabel").style.display = "none";
            document.getElementById("phonenumber").style.display = "none";
            document.getElementById("phonenumberlabel").style.display = "none";
            document.getElementById("email").style.display = "none";
            document.getElementById("emaillabel").style.display = "none";
            document.getElementById("formsubmit").style.display = "block";
        break;
        case "Surname":
            document.getElementById("firstname").style.display = "none";
            document.getElementById("firstnamelabel").style.display = "none";
            document.getElementById("surname").style.display = "block";
            document.getElementById("surnamelabel").style.display = "block";
            document.getElementById("dob").style.display = "none";
            document.getElementById("doblabel").style.display = "none";
            document.getElementById("phonenumber").style.display = "none";
            document.getElementById("phonenumberlabel").style.display = "none";
            document.getElementById("email").style.display = "none";
            document.getElementById("emaillabel").style.display = "none";
            document.getElementById("formsubmit").style.display = "block";
        break;
        case "Date of Birth":
            document.getElementById("firstname").style.display = "none";
            document.getElementById("firstnamelabel").style.display = "none";
            document.getElementById("surname").style.display = "none";
            document.getElementById("surnamelabel").style.display = "none";
            document.getElementById("dob").style.display = "block";
            document.getElementById("doblabel").style.display = "block";
            document.getElementById("phonenumber").style.display = "none";
            document.getElementById("phonenumberlabel").style.display = "none";
            document.getElementById("email").style.display = "none";
            document.getElementById("emaillabel").style.display = "none";
            document.getElementById("formsubmit").style.display = "block";
        break;
        case "Phone Number":
            document.getElementById("firstname").style.display = "none";
            document.getElementById("firstnamelabel").style.display = "none";
            document.getElementById("surname").style.display = "none";
            document.getElementById("surnamelabel").style.display = "none";
            document.getElementById("dob").style.display = "none";
            document.getElementById("doblabel").style.display = "none";
            document.getElementById("phonenumber").style.display = "block";
            document.getElementById("phonenumberlabel").style.display = "block";
            document.getElementById("email").style.display = "none";
            document.getElementById("emaillabel").style.display = "none";
            document.getElementById("formsubmit").style.display = "block";
        break;
        case "Email Address":
            document.getElementById("firstname").style.display = "none";
            document.getElementById("firstnamelabel").style.display = "none";
            document.getElementById("surname").style.display = "none";
            document.getElementById("surnamelabel").style.display = "none";
            document.getElementById("dob").style.display = "none";
            document.getElementById("doblabel").style.display = "none";
            document.getElementById("phonenumber").style.display = "none";
            document.getElementById("phonenumberlabel").style.display = "none";
            document.getElementById("email").style.display = "block";
            document.getElementById("emaillabel").style.display = "block";
            document.getElementById("formsubmit").style.display = "block";
        break;

    }
}

