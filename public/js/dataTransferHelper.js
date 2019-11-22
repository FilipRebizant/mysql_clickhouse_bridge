$(document).ready(function () {
    var expanded = false;
    function showCheckboxes() {
        var checkboxes = document.getElementById("checkboxes");
        if (!expanded) {
            checkboxes.style.display = "block";
            expanded = true;
        } else {
            checkboxes.style.display = "none";
            expanded = false;
        }
    }

    $('.selectBox').click(showCheckboxes);

    $('#form').submit(function (e) {
        e.preventDefault();

        var progressBar = $('#progress-bar');
        var progressBarWrapper = $('.progress');
        var textHelper = $('#text-helper');
        var wrapper = $('.alert-success');
        var selections = '';
        var startedAt = new Date();
        var difference, finishedAt;
        var url = $(this).attr('action');
        var i = 1;
        var form = $(this);
        var progress;

        showCheckboxes();

        form.fadeOut();
        textHelper.fadeIn();

        document.querySelectorAll('input[type=checkbox]').forEach(function(item) {
            if (item.checked) {
                selections = selections + item.value + ',';
            }
        });

        selections = selections.substr(0, selections.length-1);

        var numberOfRows;
        getNumberOfRows(selections).then(function (response) {
            progressBarWrapper.fadeIn();
            numberOfRows = response.result.numberOfRows;
        });
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
                if(result.rows.length) {
                    setTimeout(check_condition, 1, i);
                } else {
                    finishedAt = new Date();
                    difference = finishedAt.getTime() - startedAt.getTime();
                    wrapper.append("Copying " + numberOfRows + " rows from columns: " + selections +  " finished in ");
                    wrapper.append(difference / 1000 + " seconds");

                    setTimeout(function () {
                        wrapper.fadeIn();
                        form.fadeIn();
                        textHelper.fadeOut();
                    }, 500);
                }

                if (numberOfRows) {
                    progress = Math.round(( ((i-3)*500) / numberOfRows) * 100);
                    progressBar.attr('aria-valuenow', progress);
                    progressBar.css({
                        width: progress+'%',
                        height: '1rem'
                    });
                }
            });

            i++;
        }
    });

    async function getNumberOfRows(columns)
    {
        return Promise.resolve($.ajax({
            url: 'mariaDB_number_of_rows/',
            method: 'post',
            data: JSON.stringify({
                'columns': columns
            })
        }));
    }
});
