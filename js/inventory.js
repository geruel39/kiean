const newItemModal = document.getElementById('new_item_modal');
const editItemModal = document.getElementById('edit_item_modal');

//Open New Item Modal
document.getElementById('new_item').onclick = ()=>{
    newItemModal.classList.remove('hidden');
}

//Add New Item
document.getElementById('add_item_btn').onclick = ()=>{
    const name = document.getElementById('item_name');
    const cost = document.getElementById('item_cost');
    const image = document.getElementById('item_image');

    const formData = new FormData();
    formData.append('image', image.files[0]);
    formData.append('name', name.value);
    formData.append('cost', cost.value);
    formData.append('newItem', true);

    fetch('z_process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.result) {
            alert('Item uploaded successfully!');
            newItemModal.classList.add('hidden');
            name.value = "";
            cost.value = "";
            image.files[0] = "";
            displayItems();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function displayItems(){

    const search = document.getElementById('search_item');
    search.onsearch = ()=>{displayItems();}
    
    const info = {
        displayItems: true,
        search: search.value
    }

    const table = document.getElementById('inventory_table');
    table.innerHTML = "";

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Items: ",data);

        if(!data.result){
            table.innerHTML = "<h1 class='text-2xl'>No Item...</h1>";
        }else{
            data.items.forEach(i=>{
                const box = document.createElement('div');
                box.setAttribute('data-id', i.item_id);
                box.setAttribute('data-item', i.item);
                box.setAttribute('data-cost', i.cost);
                box.setAttribute('data-image', i.image);
                box.classList.add('e_item');
                box.innerHTML = `
                    <div class="m-2 flex flex-col cursor-pointer shadow shadow-md border-2 hover:border-blue-500" title="${i.item}">
                        <img src="images/inventory/${i.image}" alt="${i.item}" class="w-56 h-40 object-cover">
                        <p class="px-1 w-56 text-xl overflow-hidden text-ellipsis whitespace-nowrap">${i.item}</p>
                        <p class="px-1 w-56 text-md overflow-hidden text-ellipsis whitespace-nowrap">Cost: ${i.cost}</p>
                    </div>
                `;

                table.append(box);
            });

            const edit = document.querySelectorAll('.e_item');
            edit.forEach(btn=>{
                btn.addEventListener('click', e=>{
                    const id = e.currentTarget.getAttribute('data-id');
                    const item = e.currentTarget.getAttribute('data-item');
                    const cost = e.currentTarget.getAttribute('data-cost');
                    const image = e.currentTarget.getAttribute('data-image');

                    document.getElementById('save_edit').setAttribute('data-id', id);

                    document.getElementById('edit_item_name').textContent = item;
                    document.getElementById('edit_item_image').src = `images/inventory/${image}`;

                    document.getElementById('n_item').value = item;
                    document.getElementById('n_cost').value = cost;
                    document.getElementById('n_image').value = '';

                    editItemModal.classList.remove('hidden');
                })
            })
        }


    })
    .catch(error => {console.error('Error Message!', error)})


}
displayItems();

//Edit Item
document.getElementById('save_edit').onclick = ()=>{
    const id = document.getElementById('save_edit').getAttribute('data-id');

    const item = document.getElementById('n_item');
    const cost = document.getElementById('n_cost');
    const image = document.getElementById('n_image');

    const formData = new FormData();
    formData.append('editItem', true);
    formData.append('id', id);
    formData.append('name', item.value);
    formData.append('cost', cost.value);
    formData.append('image', image.files[0]);

    fetch('z_process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log(data)
        if(data.result) {
            editItemModal.classList.add('hidden');
            displayItems();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });


}


//Close all popup
const cancelBtn = document.querySelectorAll('.close-popup');
cancelBtn.forEach(btn=>{
    btn.addEventListener('click', ()=>{
        newItemModal.classList.add('hidden');
        editItemModal.classList.add('hidden');
    })
})