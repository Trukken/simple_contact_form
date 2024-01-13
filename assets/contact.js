import './app.js'
import {isVisible} from "bootstrap/js/src/util";
const $ = require('jquery');

$('form').submit(function (event){
    event.preventDefault();
    let ajaxOptions = {
        headers: {
            Accept : "application/json",
            "Content-Type": "application/json"
        },
        dataType: "json",
        url:'/contact/new',
        type: "POST",
        data: JSON.stringify({
            'name': $('#name').val(),
            'email': $('#email').val(),
            'message': $('#message').val()
        }),
    }
    let responseDiv = $('.response');
    $.ajax({
        ...ajaxOptions,
        success: (function (response) {
            console.log(response)
            responseDiv.empty()
            responseDiv.show();
            responseDiv.removeClass('alert-danger')
            responseDiv.addClass('alert-primary')
            responseDiv.append('<p>' + response + '</p>');
        }),
        error: (function (response) {
            console.log(response)
            responseDiv.empty()
            responseDiv.show();
            responseDiv.removeClass('alert-primary')
            responseDiv.addClass('alert-danger')
            responseDiv.append('<p>' + response.responseJSON + '</p>');
        })
    });
});
