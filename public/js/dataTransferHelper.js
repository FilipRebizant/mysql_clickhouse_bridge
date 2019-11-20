$('#form').submit(function (e) {
    e.preventDefault();

    var selections = '';
    var startedAt = new Date();
    var difference, finishedAt;
    var url = $(this).attr('action');
    var i = 1;

    document.querySelectorAll('input[type=checkbox]').forEach(function(item) {
        if (item.checked) {
            selections = selections + item.value + ',';
        }
    });

    selections = selections.substr(0, selections.length-1);

    check_condition(i);
    function check_condition(i) {
        $.ajax({
            url: url,
            method: 'post',
            data: JSON.stringify({
                'columns': selections,
                'from': 'mariaDB',
                'counter': i
            })
        }).done(function (result) {
            console.log(result);
            if(result.rows.length) {
                setTimeout(check_condition, 50, i);
            } else {
                var wrapper = $('.alert-success');
                finishedAt = new Date();
                difference = finishedAt.getTime() - startedAt.getTime();
                wrapper.append('<p>Kopiowanie danych z kolumn: ' + selections +  ' zakończone</p>');
                wrapper.append("Zajęło " + difference / 1000 + " sekund");
                wrapper.fadeIn();}
        });

        i++;
    }
});
