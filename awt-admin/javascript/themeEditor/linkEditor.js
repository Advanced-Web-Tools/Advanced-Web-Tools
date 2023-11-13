function linkEditor(input, output)
{
    var url = $(input).val();
    $(output).attr('href', url);
}