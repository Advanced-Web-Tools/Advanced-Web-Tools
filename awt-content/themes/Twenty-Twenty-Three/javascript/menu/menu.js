$(document).ready(function(){
    $('.hamburger-menu').click(function(){
        $('.main-navigation').toggleClass('active');
        $('.main-navigation ul').toggleClass('active');
        $('.hamburger-menu').toggleClass('active');
    });
});
