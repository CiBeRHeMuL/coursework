function FileUpload(config, $) {
    this.id = config.id;
    this.url = config.url;

    document.querySelector('input[type=file]').addEventListener('change', (e) => {
        e.stopPropagation();
        let text = '';
        if (e.target.files instanceof FileList && e.target.files.length) {
            if (e.target.files[0].name.length > 20) {
                text = e.target.files[0].name.substring(0, 17) + '...';
            } else {
                text = e.target.files[0].name;
            }
        }
        document.querySelector('#' + this.id + '-file-name').textContent = text;
        if (e.target.files instanceof FileList && e.target.files.length) {
            let formData = new FormData();
            formData.append(e.target.files[0].name, e.target.files[0], e.target.files[0].name);
            $.ajax({
                url: this.url,
                type: 'POST',
                mimeType: 'multipart/form-data',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: (data, textStatus, jqXHR) => {
                    let success = false;
                    let results = [];
                    try {
                        const parsedData = JSON.parse(data);
                        success = parsedData.success;
                        results = parsedData.results;
                    } catch (e) {
                        success = false;
                        results = [];
                    }

                    if (success && results) {
                        const result = results[0];
                        let {
                            "key": key,
                            "success": success,
                            "message": message,
                            "uploaded_url": uploaded_url,
                        } = result;
                        if (success) {
                            let input = document.querySelector('#' + this.id + '-success-result');
                            const inputName = input.getAttribute('name');
                            let form = input.closest('form');
                            form.querySelectorAll('input[name="' + inputName + '"]').forEach((el) => {
                                el.setAttribute('value', uploaded_url);
                            });
                        } else {
                            document.querySelector('#' + this.id + '-file-error').textContent = message;
                        }
                    } else {
                        document.querySelector('#' + this.id + '-file-error').textContent = 'Ошибка загрузки';
                    }
                },
                error: (jqXHR, textStatus, errorThrown) => {
                    document.querySelector('#' + this.id + '-file-error').textContent = 'Ошибка загрузки';
                },
            });
        }
    });
    document.querySelector('#' + this.id + '-open-file').addEventListener('click', (e) => {
        if (e.target.querySelector('input[type=file]')) {
            e.stopPropagation();
            e.target.querySelector('input').click();
        }
    });
    document.querySelector('#' + this.id + '-reset-button').addEventListener('click', (e) => {
        e.stopPropagation();
        document.querySelector('#' + this.id + '-file-input').files = null;
        let input = document.querySelector('#' + this.id + '-success-result');
        const inputName = input.getAttribute('name');
        let form = input.closest('form');
        form.querySelectorAll('input[name="' + inputName + '"]').forEach((el) => {
            el.removeAttribute('value');
        });
        document.querySelector('#' + this.id + '-file-name').textContent = '';
    });
}

window.FileUpload = FileUpload;
