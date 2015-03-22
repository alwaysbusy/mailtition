// Cheap and dirty global variables

var stage = 0;

// Navigation functions
function navBack() {
    moveStage(-1);
}

function navNext() {
    moveStage(1);
}

function moveStage(change){
    if (stage + change >= 0) {
        stage += change;
    }
    
    if (stage === 0) {
        window.document.getElementById('nav-back').style.display = 'none';
    } else {
        window.document.getElementById('nav-back').style.display = 'inline-block';
    }
    
    switch (stage) {
        case 0:
            hideAllExceptIntro();
            window.document.getElementById('nav-next').innerHTML = 'Start';
            break;
        case 1:
            window.document.getElementById('intro').style.display = 'none';
            window.document.getElementById('customise').style.display = 'none';
            window.document.getElementById('personal').style.display = 'block';
            window.document.getElementById('nav-next').innerHTML = 'Next';
            break;
        case 2:
            if (!personalValid()) {
                stage = 1;
                break;
            }
            window.document.getElementById('personal').style.display = 'none';
            window.document.getElementById('confirm').style.display = 'none';
            window.document.getElementById('customise').style.display = 'block';
            window.document.getElementById('nav-next').innerHTML = 'Next';
            if (window.document.getElementsByName('0-inc').length === 0) {
                loadLetter();
            }
            break;
        case 3:
            if (!validateLetter()) {
                stage = 2;
                break;
            }
            window.document.getElementById('customise').style.display = 'none';
            window.document.getElementById('confirm').style.display = 'block';
            window.document.getElementById('nav-next').innerHTML = 'Send';
            break;
        case 4:
            if (!ajaxSubmit()) {
                stage = 3;
                break;
            }
            window.document.getElementById('confirm').style.display = 'none';
            window.document.getElementById('sent').style.display = 'block';
            window.document.getElementsByTagName('nav')[0].style.display = 'none';
    }
}

//Load window
function hideAllExceptIntro(){
    window.document.getElementById('intro').style.display = 'block';
    window.document.getElementById('personal').style.display = 'none';
    window.document.getElementById('customise').style.display = 'none';
    window.document.getElementById('confirm').style.display = 'none';
    window.document.getElementById('sent').style.display = 'none';
}

//Validate personal
function personalValid(){
    if (window.document.forms.letter.firstname.value != '' && window.document.forms.letter.lastname.value != '') {
        //Name valid
        if (window.document.forms.letter.email.value.indexOf('@') > 0) {
            //Email address possibly valid
            if (window.document.forms.letter.email.value.split('@')[1].indexOf('.') > 0){
                //Email valid
                return true;
            } else {
                alert('Please enter a complete e-mail address');
            }
        } else {
            alert('Please enter a complete e-mail address');
        }
    } else {
        alert('Please enter your full name');
    }
    return false;
}

//Letter functions
function loadLetter() {
    var ajax = ajaxRequest();
    ajax.open('GET', 'templates/letter.json', false);
    ajax.send(null);
    var lettercontent = JSON.parse(ajax.response);
    var lettertable = window.document.getElementById('customise-table');
    for (var i = 0; i < lettercontent.sections.length; i++) {
        var newElem = window.document.createElement('tr');
        //Set Include Checkbox
        var newInclude = window.document.createElement('td');
        var newIncludeBox = window.document.createElement('input');
        newIncludeBox.setAttribute('type', 'checkbox');
        newIncludeBox.setAttribute('name', i + '-inc');
        if (lettercontent.sections[i].required) {
            newIncludeBox.setAttribute('checked', 'checked');
            newIncludeBox.setAttribute('disabled', 'disabled');
        } else if (lettercontent.sections[i].value != "") {
            newIncludeBox.setAttribute('checked', 'checked');
        }
        newInclude.appendChild(newIncludeBox);
        newElem.appendChild(newInclude);
        //Set Actual contents
        var newContent = window.document.createElement('td');
        newContent.setAttribute('class', lettercontent.sections[i].type);
        if (!lettercontent.sections[i].editable) {
            var newContentText = window.document.createElement('p');
            newContentText.appendChild(window.document.createTextNode(lettercontent.sections[i].value));
            newContent.appendChild(newContentText);
        } else {
            var newContentText = window.document.createElement('textarea');
            newContentText.setAttribute('name', i + '-val');
            newContentText.appendChild(window.document.createTextNode(lettercontent.sections[i].value));
            newContent.appendChild(newContentText);
        }
        newElem.appendChild(newContent);
        lettertable.appendChild(newElem);
    }
}

function validateLetter() {
    var valid = true;
    var i = 0;
    while (window.document.getElementsByName(i + '-inc').length > 0) {
        if (window.document.getElementsByName(i + '-inc')[0].checked) {
            if (window.document.getElementsByName(i + '-val').length > 0) {
                if (window.document.getElementsByName(i + '-val')[0].value == '') {
                    valid = false;
                    alert('Please fill in all customisable parts of the letter you have chosen.');
                }
            }
        } else if (window.document.getElementsByName(i + '-inc')[0].disabled) {
            valid = false;
            alert('You have removed a required part of the letter');
        }
        i++;
    }
    return valid;
}

function ajaxSubmit( ){
    //Generate POST string
    var postString = 'firstname=' + encodeURIComponent(window.document.forms.letter.firstname.value) + '&lastname=' + encodeURIComponent(window.document.forms.letter.lastname.value) + '&email=' + encodeURIComponent(window.document.forms.letter.email.value);
    
    var i = 0;
    while (window.document.getElementsByName(i + '-inc').length > 0) {
        postString += '&' + i + '-inc=' + window.document.getElementsByName(i + '-inc')[0].checked;
        if (window.document.getElementsByName(i + '-val').length > 0) {
            postString += '&' + i + '-val=' + encodeURIComponent(window.document.getElementsByName(i + '-val')[0].value);
        }
        i++;
    }
    //Send via ajax
    var ajax = ajaxRequest();
    ajax.open('POST','store.php',false);
    ajax.setRequestHeader('Content-type','application/x-www-form-urlencoded');
    ajax.send(postString);
    
    //Process response
    if(ajax.responseText == 'QUEUE'){
        return true;
    } else {
        alert('Please check your letter and try again');
        return false;
    }
}

//Ajax element
function ajaxRequest() {
    if (window.XMLHttpRequest) {
        return new XMLHttpRequest();
    } else {
        return new ActiveXObject('Microsoft.XMLHTTP');
    }
}