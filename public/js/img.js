// window.onload = () => {
//     // delete button
//     let links = document.querySelectorAll("[data-delete]")

//   // link in the delete button
//     for(link of links){
//         // listening to clicks
//         link.addEventListener("click", function(e){
//             // deactivating the link
//             e.preventDefault()

//             // asking to confirm deleting the photo
//             if(confirm("Do you wish to delete this photo?")){
//                 // request to href containing DELETE 
//                 fetch(this.getAttribute("href"), {
//                     method: "DELETE",
//                     headers: {
//                         "X-Requested-With": "XMLHttpRequest",
//                         "Content-Type": "application/json"
//                     },
//                     body: JSON.stringify({"_token": this.dataset.token})
//                 }).then(
//                     // get response in json
//                     response => response.json()
//                 ).then(data => {
//                     if(data.success)
//                         this.parentElement.remove()
//                     else
//                         alert(data.error)
//                 }).catch(e => alert(e))
//             }
//         })
//     }
// }