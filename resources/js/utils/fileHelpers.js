import fileType from 'file-type';

function readFileAsync(file, method = "readAsDataURL") {
    return new Promise((resolve, reject) => {
        let reader = new FileReader();
        reader.onload = () => resolve(reader.result);
        reader.onerror = reject;
        reader[method](file);
    });
}

/**
 * 
 * @param {*} file 
 * @param Array allowedFileTypes 
 */
function checkFileMimetype(file, allowedFileTypes) {
    return readFileAsync(file, "readAsArrayBuffer").then(result => {
        let ft = fileType(result);
        return ft && allowedFileTypes.includes(ft.mime);
    });
}

export {
    readFileAsync,
    checkFileMimetype
}