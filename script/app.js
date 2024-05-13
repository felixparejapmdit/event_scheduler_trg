// declearing html elements

const imgDiv = document.querySelector('.profile-picture-container');
const img = document.querySelector('#photo');
const file = document.querySelector('#file');
const uploadBtn = document.querySelector('#upload-btn');

// if user hover on profile

imgDiv.addEventListener('mouseenter', function() {
    uploadBtn.style.display = "block";
})

// if we hover out from profile div

imgDiv.addEventListener('mouseleave', function() {
    uploadBtn.style.display = "none";
})

// lets work for image showing functionality 
// when we choose an image to upload

file.addEventListener('change', function() {
    // this refers to file
    const choosedFile = this.files[0];

    if(choosedFile) {

        const reader = new FileReader();
        reader.addEventListener('load', function() {
            img.setAttribute('src', reader.result)
        });

        reader.readAsDataURL(choosedFile);


    }

})