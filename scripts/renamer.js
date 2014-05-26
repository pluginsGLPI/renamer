

function searchOriginalWord(){

    var word = $("#original").val();
    var img_wait = $("#wait");
    var div_info = $("#infoSearchWord");
    var lang = $("#dropdown_language").find(":selected").text();;
    var div_info2 = $("#infoOverloadWord");


    img_wait.css('display', 'block');
    div_info.empty();
    div_info2.empty();

    $.ajax({ // fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "../ajax/ajax.php", // url du fichier php
        data: "action=searchOriginalWord&" +
            "original=" + word +"&" +
            "lang=" + lang, // données à transmettre
        success: function (msg) { // si l'appel a bien fonctionné


            if (msg.indexOf('check') != -1 || msg.indexOf('cross') != -1) {
                img_wait.css('display', 'none');
                div_info.append(msg);
            }else{
                img_wait.css('display', 'none');
                div_info2.append(msg);

            }


        },
        error: function () {

            img_wait.css('display', 'none');
            div_info.append("Ajax problem !");

        }

    });
    return false; // permet de rester sur la même page à la soumission du formulaire

}


function overloadWord(){

    var word = $("#original").val();
    var lang = $("#dropdown_language").find(":selected").text();
    var overload = $("#overload").val();
    var userid = $("#users_id").val();
    var date = $("#date_overload").val();

    var img_wait = $("#wait");
    var div_info = $("#infoOverloadWord");


    img_wait.css('display', 'block');
    div_info.empty();

    $.ajax({ // fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "../ajax/ajax.php", // url du fichier php
        data: "action=overloadWord&" +
            "original=" + word +"&" +
            "lang=" + lang+"&" +
            "overload=" + overload+"&" +
            "users_id=" + userid+"&" +
            "date_overload=" + date, // données à transmettre
        success: function (msg) { // si l'appel a bien fonctionné

            img_wait.css('display', 'none');

            if (msg == true) {
                popupOverloadLanguage.hide();
                window.location.reload();
            }else{
                div_info.append(msg);
                return false;
            }

        },
        error: function () {

            img_wait.css('display', 'none');
            div_info.append("Ajax problem !");
            return false;
        }

    });
}


function restoreLocaleFiles(){

    $.ajax({ // fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "../ajax/ajax.php", // url du fichier php
        data: "action=restore", // données à transmettre
        success: function (msg) { // si l'appel a bien fonctionné



            window.location.reload();

        },
        error: function () {

            alert("Ajax problem");
        }

    });
}

function restoreWord(id){



    $.ajax({ // fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "../ajax/ajax.php", // url du fichier php
        data: "action=restoreWord&"+
            "id=" + id, // données à transmettre
        success: function (msg) { // si l'appel a bien fonctionné

            window.location.reload();

        },
        error: function () {

            alert("Ajax problem");
        }

    });
}


function updateOverloadWord(id , input){



    var img_wait = $("#waitUpdateOverload");
    var div_info = $("#infoUpdateOverloadWord");
    var popupName = "popupToUpdate" + id;
    var new_word = $("#newoverload"+id).val();

    img_wait.css('display', 'block');
    div_info.empty();

    $.ajax({ // fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "../ajax/ajax.php", // url du fichier php
        data: "action=updateWord&"+
            "id=" + id+"&"+
        "new_word=" + new_word, // données à transmettre
        success: function (msg) { // si l'appel a bien fonctionné

            if (msg ) {

                eval(popupName).hide();
                window.location.reload();

            }else {

                img_wait.css('display', 'none');
                div_info.append(msg);

            }



        },
        error: function () {

            alert("Ajax problem");
        }

    });
}