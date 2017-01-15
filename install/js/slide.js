function slide(
    idDiv
)
{
    $(document).ready(function() {
        $('#'+idDiv).slideToggle('slow');
    })
}
