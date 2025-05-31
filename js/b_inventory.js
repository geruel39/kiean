const editQuantity = document.getElementById('edit_q');
const invActions = document.getElementById('inv_actions');

document.getElementById('open_inv_actions').onclick = ()=>{
    invActions.classList.remove('hidden');
}

function displayItems(){

    const search = document.getElementById('search_item');
    search.onsearch = ()=>{displayItems();}
    
    const info = {
        displayItems_q: true,
        search: search.value,
        branch: sessionStorage.getItem('branch')
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

                if(!i.quantity){
                    i.quantity = 0;
                }

                const box = document.createElement('div');
                box.setAttribute('data-id', i.item_id);
                box.setAttribute('data-item', i.item);
                box.setAttribute('data-cost', i.cost);
                box.classList.add('e_item');
                box.innerHTML = `
                    <div class="m-2 flex flex-col cursor-pointer shadow shadow-md border-2 hover:border-blue-500" title="${i.item}">
                        <img src="images/inventory/${i.image}" alt="${i.item}" class="w-56 h-40 object-cover">
                        <p class="px-1 w-56 text-xl overflow-hidden text-ellipsis whitespace-nowrap">${i.item}</p>
                        <h1 class="px-1 w-56 text-3xl overflow-hidden text-ellipsis whitespace-nowrap">${i.quantity}</h1>
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

                    document.getElementById('item_name').textContent = item;
                    document.getElementById('item_cost').textContent = "Cost: " + cost;

                    document.getElementById('add_q').setAttribute('data-id', id);
                    document.getElementById('ded_q').setAttribute('data-id', id);


                    editQuantity.classList.remove('hidden');
                })
            })
        }


    })
    .catch(error => {console.error('Error Message!', error)})


}
setTimeout(function() {displayItems();}, 1500);

document.getElementById('add_q').onclick = ()=>{
    const id = document.getElementById('add_q').getAttribute('data-id');
    const quantity = document.getElementById('item_q');

    if(!quantity){
        alert('Enter the quantity');
        return;
    }

    const info = {
        addQuantity: true,
        id: id,
        quantity: quantity.value,
        branch: sessionStorage.getItem('branch')
    }

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Add Quantity: ",data);

        if(!data.result){
            alert(data.message);
        }else{
            displayItems();
            displayActions();
            editQuantity.classList.add('hidden');
            quantity.value = "";
        }
    })
    .catch(error => {console.error('Error Message!', error)})

}

document.getElementById('ded_q').onclick = ()=>{
    const id = document.getElementById('add_q').getAttribute('data-id');
    const quantity = document.getElementById('item_q');

    if(!quantity){
        alert('Enter the quantity');
        return;
    }

    const info = {
        dedQuantity: true,
        id: id,
        quantity: quantity.value,
        branch: sessionStorage.getItem('branch')
    }

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Deduct Quantity: ",data);

        if(!data.result){
            alert(data.message);
        }else{
            displayItems();
            displayActions();
            editQuantity.classList.add('hidden');
            quantity.value = "";
        }
    })
    .catch(error => {console.error('Error Message!', error)})

}

function displayActions(){
    const info = {
        displayActions: true,
        branch: sessionStorage.getItem('branch')
    }

    const table = document.getElementById('action_table');
    table.innerHTML = "";

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Invetory Actions: ",data);

        if(!data.result){
            table.innerHTML = `
                <div class="flex p-2 border border-gray-500 rounded">
                    <p class="w-full text-center">No Result.</p>
                </div> 
            `;
        }else{
            data.actions.forEach(a=>{

                let action = a.action == "A" ? "Added" : "Deduct"; 
                let color = a.action == "A" ? "text-green-500" : "text-red-500"

                const row = document.createElement('div');
                row.innerHTML = `
                    <div class="flex p-2 border-gray-500 rounded hover:bg-gray-300">
                        <p class="w-full ${color}">${action}</p>
                        <p class="w-full">${a.name}</p>
                        <p class="w-full">${a.quantity}</p>
                        <p class="w-full">${a.cost}</p>
                        <p class="w-full">${a.date}</p>
                        <p class="w-full">${a.time}</p>
                    </div>
                `;

                table.append(row);
            })
        }


    })
    .catch(error => {console.error('Error Message!', error)})
}
setTimeout(function() {displayActions();}, 1500);

document.getElementById('undo_action').onclick = ()=>{
    
    let confirm = window.confirm('Do you want to undo previous action?');
    if(!confirm){
        return;
    }

    const info = {
        undoAction: true,
        branch: sessionStorage.getItem('branch')
    }

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Undo Action: ",data);
        displayActions();
        displayItems();
    })
    .catch(error => {console.error('Error Message!', error)})
}

//Close all popup
const cancelBtn = document.querySelectorAll('.close-popup');
cancelBtn.forEach(btn=>{
    btn.addEventListener('click', ()=>{
        editQuantity.classList.add('hidden');
        invActions.classList.add('hidden');
    })
})