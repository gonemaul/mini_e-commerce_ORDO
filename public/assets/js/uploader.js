const fileList=document.querySelector(".file-list");const fileBrowseButton=document.querySelector(".file-browse-button");const fileBrowseInput=document.querySelector(".file-browse-input");const fileUploadBox=document.querySelector(".file-upload-box");const fileCompletedStatus=document.querySelector(".file-completed-status");const removeBtn=document.querySelector('.remove-image');const cancelSubmit=document.querySelector('#cancel-submit');const submit=document.querySelector('#submit');const path_image=document.querySelector('#path_image')
let totalFiles=0;let completedFiles=0;let images=[];let isFormDirty=!1;const createFileItemHTML=(file,uniqueIdentifier)=>{const{name,size}=file;const formattedFileSize=size>=1024*1024?`${(size / (1024 * 1024)).toFixed(2)} MB`:`${(size / 1024).toFixed(2)} KB`;let previewURL='';if(file.type.startsWith('image/')){previewURL=URL.createObjectURL(file)}
return `<li class="file-item" id="file-item-${uniqueIdentifier}">
                <div class="file-extension">
                ${previewURL ? `<img src="${previewURL}" alt="${name}" class="file-preview"/>` : ''}
                </div>
                <div class="file-content-wrapper">
                <div class="file-content">
                    <div class="file-details">
                    <h5 class="file-name">${name}</h5>
                    <div class="file-info">
                        <small class="file-size">0 MB / ${formattedFileSize}</small>
                        <small class="file-divider">•</small>
                        <small class="file-status">Uploading...</small>
                    </div>
                    </div>
                    <button class="cancel-button">
                        <i class="bx bx-x"></i>
                    </button>
                    <label class="remove-image">
                        <i class='bx bxs-trash'></i>
                    </label>
                </div>
                <div class="file-progress-bar">
                    <div class="file-progress"></div>
                </div>
                </div>
            </li>`}
const handleFileUploading=(file,uniqueIdentifier)=>{const xhr=new XMLHttpRequest();const formData=new FormData();formData.append("image",file);const csrf=document.querySelector('meta[name="csrf-token"]').getAttribute('content');formData.append('_token',csrf);xhr.upload.addEventListener("progress",(e)=>{const fileProgress=document.querySelector(`#file-item-${uniqueIdentifier} .file-progress`);const fileSize=document.querySelector(`#file-item-${uniqueIdentifier} .file-size`);const formattedFileSize=file.size>=1024*1024?`${(e.loaded / (1024 * 1024)).toFixed(2)} MB / ${(e.total / (1024 * 1024)).toFixed(2)} MB`:`${(e.loaded / 1024).toFixed(2)} KB / ${(e.total / 1024).toFixed(2)} KB`;const progress=Math.round((e.loaded/e.total)*100);fileProgress.style.width=`${progress}%`;fileSize.innerText=formattedFileSize});xhr.onload=()=>{if(xhr.status===200){var response=JSON.parse(xhr.responseText);console.log(response.message)}else{console.log("Failed to upload")}}
xhr.open("POST","/products/upload-image",!0);xhr.send(formData);return xhr}
const handleSelectedFiles=([...files])=>{if(files.length===0)return;totalFiles+=files.length;isFormDirty=!0;files.forEach((file,index)=>{const uniqueIdentifier=Date.now()+index;const fileItemHTML=createFileItemHTML(file,uniqueIdentifier);fileList.insertAdjacentHTML("afterbegin",fileItemHTML);const currentFileItem=document.querySelector(`#file-item-${uniqueIdentifier}`);const cancelFileUploadButton=currentFileItem.querySelector(".cancel-button");const deleteFile=currentFileItem.querySelector(".remove-image");const filePreview=currentFileItem.querySelector('.file-preview');deleteFile.style.display='none';const xhr=handleFileUploading(file,uniqueIdentifier);const updateFileStatus=(status,color)=>{currentFileItem.querySelector(".file-status").innerText=status;currentFileItem.querySelector(".file-status").style.color=color}
xhr.addEventListener("readystatechange",()=>{if(xhr.readyState===XMLHttpRequest.DONE&&xhr.status===200){var response=JSON.parse(xhr.responseText);completedFiles++;cancelFileUploadButton.remove();deleteFile.style.display='inline-block';deleteFile.setAttribute('image_path',response.path)
images.push(response.path);path_image.setAttribute('value',JSON.stringify(images));updateFileStatus(response.status,response.color);fileCompletedStatus.innerText=`${completedFiles} / ${totalFiles} files completed`}});deleteFile.addEventListener('click',()=>{const path=deleteFile.getAttribute('image_path');const remove=handleDeleteFiles(path);images=images.filter(img=>img!==path);path_image.setAttribute('value',JSON.stringify(images));remove.addEventListener('readystatechange',()=>{if(remove.readyState===XMLHttpRequest.DONE&&remove.status===200){var response=JSON.parse(remove.responseText);updateFileStatus(response.status,response.color);deleteFile.style.display='none';filePreview.classList.add('removed')}})});cancelFileUploadButton.addEventListener("click",()=>{xhr.abort();updateFileStatus("Cancelled","#E3413F");cancelFileUploadButton.remove()});xhr.addEventListener("error",()=>{updateFileStatus("Error","#E3413F");alert("An error occurred during the file upload!")})});fileCompletedStatus.innerText=`${completedFiles} / ${totalFiles} files completed`}
const handleDeleteFiles=(path)=>{const xhr=new XMLHttpRequest();const formData=new FormData();formData.append("image",path);const csrf=document.querySelector('meta[name="csrf-token"]').getAttribute('content');formData.append('_token',csrf);xhr.onload=()=>{if(xhr.status===200){var response=JSON.parse(xhr.responseText);console.log(response.message)}else{console.log("Failed to delete")}}
xhr.open("POST","/products/delete-image",!0);xhr.send(formData);return xhr}
function removeFiles(){if(images.length!==0){images.forEach((image,index)=>{handleDeleteFiles(image)})}}
function cancel(){if(images.length===0){window.location.href='/products'}
removeFiles();window.location.href='/products'}
window.addEventListener('beforeunload',function(event){if(isFormDirty){event.preventDefault()}});fileUploadBox.addEventListener("drop",(e)=>{e.preventDefault();handleSelectedFiles(e.dataTransfer.files);fileUploadBox.classList.remove("active");fileUploadBox.querySelector(".file-instruction").innerText="Drag files here or"});fileUploadBox.addEventListener("dragover",(e)=>{e.preventDefault();fileUploadBox.classList.add("active");fileUploadBox.querySelector(".file-instruction").innerText="Release to upload or"});fileUploadBox.addEventListener("dragleave",(e)=>{e.preventDefault();fileUploadBox.classList.remove("active");fileUploadBox.querySelector(".file-instruction").innerText="Drag files here or"});fileBrowseInput.addEventListener("change",(e)=>handleSelectedFiles(e.target.files));fileBrowseButton.addEventListener("click",()=>fileBrowseInput.click());submit.addEventListener('click',function(){isFormDirty=!1
if(imagesToRemove.length!==0){imagesToRemove.forEach((image,index)=>{handleDeleteFiles(image)})}})
