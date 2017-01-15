function checkLanguage(
    value
)
{
    $(document).ready(function() {
        if (value != 'default') {
            $('#returnCheckLanguage').css("display","block");
        } else {
            $('#returnCheckLanguage').css("display","none");
        }
    });
}
