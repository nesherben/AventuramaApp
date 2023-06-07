


document.querySelector("#terminos").addEventListener('click', () => {

        var btn = document.querySelector("#registrobtn");
        var check = document.querySelector("#terminos");
        var checked = false;

        if (check.checked) {
            checked = true;
        }

        if (checked) {
            btn.disabled = false;
        } else {
            btn.disabled = true;
        }

    });
