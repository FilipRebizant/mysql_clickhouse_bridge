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

        var $form = $(this);
        var progressBar = $('#progress-bar');
        var progressBarWrapper = $('.progress');
        var textHelper = $('#text-helper');
        var $alertWrapper = $('.alert');
        var selections = '';
        var startedAt = new Date();
        var difference, finishedAt;
        var url = $form.attr('action');
        var i = 1;
        var progress;
        var numberOfRows;
        var $inputFrom = $form.find('#from');
        var $spinner = $('#spinner');

        document.querySelectorAll('input[type=checkbox]').forEach(function(item) {
            if (item.checked) {
                selections = selections + item.value + ',';
            }
        });

        selections = selections.substr(0, selections.length-1);

        if (selections) {
            $spinner.fadeIn();
            showCheckboxes();
            $form.fadeOut();
            textHelper.fadeIn();
            getNumberOfRows(selections).then(function (response) {
                progressBarWrapper.fadeIn();
                numberOfRows = response.result.numberOfRows;
            });
            check_condition(i);
        } else {
            $alertWrapper.removeClass('alert-success');
            $alertWrapper.addClass('alert-danger');
            $alertWrapper.text('Please select at least one column');
            $alertWrapper.fadeIn();
            console.log('error');
        }

        function check_condition(i) {
            $alertWrapper.fadeOut();
            $.ajax({
                url: url,
                method: 'post',
                data: JSON.stringify({
                    'columns': selections,
                    'from': $inputFrom.val(),
                    'counter': i
                })
            }).done(function (result) {
                if(result.rows.length) {
                    setTimeout(check_condition, 1, i);
                } else {
                    finishedAt = new Date();
                    difference = finishedAt.getTime() - startedAt.getTime();
                    $alertWrapper.text('');
                    $alertWrapper.append("Copying " + numberOfRows + " rows from columns: " + selections +  " finished in ");
                    $alertWrapper.append(difference / 1000 + " seconds");

                    setTimeout(function () {
                        $alertWrapper.removeClass('alert-danger');
                        $alertWrapper.addClass('alert-success');
                        $alertWrapper.fadeIn();
                        $form.fadeIn();
                        textHelper.fadeOut();
                        $spinner.fadeOut();
                    }, 500);
                }

                if (numberOfRows) {
                    progress = Math.round(( ((i-2)*500) / numberOfRows) * 100);
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
