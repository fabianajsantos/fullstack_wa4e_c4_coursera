function doValidate() {
    console.log('Validating...');
    try {
        pass = document.getElementById('id_1723').value;
        email = document.getElementById('nam').value;

        //Blank field test
        if (pass == null || pass == "" ) {
            alert("Both fields must be filled out");
            return false;

        }
            else if(email == null || email == "")
        {

            //Missing e-mail test
            const emailRegex =
                new RegExp(/^[A-Za-z0-9_!#$%&'*+\/=?`{|}~^.-]+@[A-Za-z0-9.-]+$/, "gm");

            const isValidEmail = emailRegex.test(email);

            console.log(isValidEmail) //true*/
            alert("Invalid e-mail address");
            return false;
        }
    return  true;
    } catch (e) {
        return false;
    }
    return false;
}