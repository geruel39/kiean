const addExpModal = document.getElementById('expenses_modal');
const addTypeModal = document.getElementById('type_modal');


document.getElementById('open-add-expenses').onclick = ()=>{
    addExpModal.classList.remove('hidden');
}
document.getElementById('open-add-type').onclick = ()=>{
    addTypeModal.classList.remove('hidden');
}

function getMonthName(monthNumber) {
    const monthNames = [
      'January', 'February', 'March', 'April', 'May', 'June',
      'July', 'August', 'September', 'October', 'November', 'December'
    ];
  
    const monthIndex = parseInt(monthNumber, 10) - 1;
  
    return monthNames[monthIndex] || 'Invalid month';
}

function displayOverview(){

    document.getElementById('overview_year').onchange = ()=>{
        displayOverview();
        document.getElementById('overview_date').textContent = getMonthName(document.getElementById('overview_month').value) + "-" + document.getElementById('overview_year').value;
    }
    document.getElementById('overview_month').onchange = ()=>{
        displayOverview();
        document.getElementById('overview_date').textContent = getMonthName(document.getElementById('overview_month').value) + "-" + document.getElementById('overview_year').value;
    }

    document.getElementById('overview_date').textContent = getMonthName(document.getElementById('overview_month').value) + "-" + document.getElementById('overview_year').value;

    const info = {
        displayOverview: true,
        month: document.getElementById('overview_month').value,
        year: document.getElementById('overview_year').value,
        branch: sessionStorage.getItem('branch')
    }

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Overview: ",data);

        const payments = data.payments ? Number(data.payments) : 0;
        const expenses = data.expenses ? Number(data.expenses) : 0;
        const supply = data.supply ? Number(data.supply) : 0;
        const income = payments - (expenses + supply);

        document.getElementById('payment').textContent = payments.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('expenses').textContent = expenses.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('supply').textContent = supply.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('income').textContent = income.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    })
    .catch(error => {console.error('Error Message!', error)})
}
setTimeout(function() {displayOverview();}, 1500);

//Expenses Select Date
const expensesYear = document.getElementById('exp_year');
const expensesMonth = document.getElementById('exp_month');
expensesYear.onchange = ()=>{
    displayExpenses();
    displaySupplyUsed();
    document.getElementById('expenses_date').textContent = getMonthName(expensesMonth.value) + "-" + expensesYear.value;
}
expensesMonth.onchange = ()=>{
    displayExpenses();
    displaySupplyUsed();
    document.getElementById('expenses_date').textContent = getMonthName(expensesMonth.value) + "-" + expensesYear.value;
}
document.getElementById('expenses_date').textContent = getMonthName(expensesMonth.value) + "-" + expensesYear.value;

//Add new expenses type
document.getElementById('add_type').onclick = ()=>{
    const type = document.getElementById('new_xtype');

    if(!type.value){
        alert('Enter type.');
        return;
    }

    const info = {
        addNewExpensesType: true,
        type: type.value,
        branch: sessionStorage.getItem('branch')
    }

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Add Type: ",data);
        if(!data.result){
            alert(data.message);
        }else{
            insertTypes();
            addTypeModal.classList.add('hidden');
            type.value = "";
        }
    })
    .catch(error => {console.error('Error Message!', error)})
}

function insertTypes(){
    const typeSelect = document.getElementById('exp_type');
    typeSelect.innerHTML = "<option value='' selected disabled>Select Type</option>";

    const info = {
        insertTypes: true,
        branch: sessionStorage.getItem('branch')
    }

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Insert Type: ",data);
        if(!data.result){
            typeSelect.disabled = true;
            typeSelect.innerHTML = "<option value='' selected disabled>No Options</option>";
        }else{
            data.types.forEach(t=>{
                const option = document.createElement('option');
                option.value = t.type;
                option.textContent = t.type;

                typeSelect.append(option);
            })
        }
    })
    .catch(error => {console.error('Error Message!', error)})
}
setTimeout(function() {insertTypes();}, 1500);

//Add Expenses
document.getElementById('add_expenses').onclick = ()=>{
    const type = document.getElementById('exp_type');
    const amount = document.getElementById('exp_amount');

    if(!amount.value){
        alert('Enter the expenses amount.');
        return;
    }

    if(!type.value){
        alert('Select the type');
        return;
    }

    const info = {
        addExpenses: true,
        type: type.value,
        amount: amount.value,
        date: `${expensesYear.value}-${expensesMonth.value}-01`,
        branch: sessionStorage.getItem('branch')
    }

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Add Expenses: ",data);
        
        if(!data.result){
            alert('Something went wrong');
        }else{
            displayExpenses();
            displayOverview();
            addExpModal.classList.add('hidden');
            type.value = "";
            amount.value = "";
        }
    })
    .catch(error => {console.error('Error Message!', error)})
}

function displayExpenses() {
    const info = {
        displayExpenses: true,
        year: expensesYear.value,
        month: expensesMonth.value,
        branch: sessionStorage.getItem('branch')
    }

    const table = document.getElementById('expenses_table');
    table.innerHTML = "";

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Display Expenses: ",data);

        if(!data.result){
            table.innerHTML = `
                    <div class="flex p-2 my-1 rounded border border-red-500 hover:bg-gray-300">
                        <p class="w-full font-bold text-center tracking-widest">No Result...</p>
                    </div>

                    <div class="flex p-2 my-1 bg-red-500">
                        <p class="w-full font-bold text-xl text-white tracking-widest">TOTAL</p>
                        <p class="w-full font-bold text-xl text-white tracking-widest">₱ 0.00</p>
                    </div>
            `;
        }else{
            let total = 0;

            data.expenses.forEach(x=>{
                const row = document.createElement('div');
                row.innerHTML = `
                    <div class="flex p-2 my-1 rounded border border-red-500 hover:bg-gray-300 cursor-pointer exp_del" data-id='${x.expenses_id}'>
                        <p class="w-full font-bold tracking-widest">${x.type}</p>
                        <p class="w-full font-bold tracking-widest">₱ ${Number(x.amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</p>
                    </div>
                `;

                table.append(row);

                total = total + Number(x.amount);
            });

            const row = document.createElement('div');
            row.innerHTML = `
                    <div class="flex p-2 my-1 bg-red-500">
                        <p class="w-full font-bold text-xl text-white tracking-widest">TOTAL</p>
                        <p class="w-full font-bold text-xl text-white tracking-widest">₱ ${total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</p>
                    </div>
            `;
            table.append(row);

            const del = document.querySelectorAll('.exp_del');
            del.forEach(d=>{
                d.addEventListener('click', e=>{
                    const id = e.currentTarget.getAttribute('data-id');

                    let confirm = window.confirm('Do you want to delete this expenses record?');
                    if(!confirm){
                        return;
                    }

                    const info = {
                        deleteExpenses: true,
                        id: id
                    }

                    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
                    .then(response => response.json())
                    .then(data =>{
                        console.log( "Delete Expenses: ",data);

                        if(!data.result){
                            alert('Something went wrong');
                        }else{
                            displayExpenses();
                        }
                    })
                    .catch(error => {console.error('Error Message!', error)})
                })
            })
        }
    })
    .catch(error => {console.error('Error Message!', error)})
}
setTimeout(function() {displayExpenses();}, 1500);

function displaySupplyUsed() {
    
    const table = document.getElementById('used_table');
    table.innerHTML = "";

    const info = {
        displaySupplyUsed: true,
        branch: sessionStorage.getItem('branch'),
        year: expensesYear.value,
        month: expensesMonth.value
    }

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Supply Used: ",data);

        if(!data.result){
            table.innerHTML = `
                    <div class="flex p-2 my-1 rounded border border-yellow-300 hover:bg-gray-300">
                        <p class="w-full font-bold text-center tracking-widest">No Result...</p>
                    </div>
            `;
        }else{
            let qtotal = 0;
            let ctotal = 0;

            data.used.forEach(u=>{
                const row = document.createElement('div');

                qtotal += Number(u.tq);
                ctotal += Number(u.tc);

                row.innerHTML = `
                    <div class="flex p-2 my-1 rounded border border-yellow-300 hover:bg-gray-300">
                        <p class="w-full font-bold tracking-widest">${u.name}</p>
                        <p class="w-full font-bold tracking-widest">${Number(u.tq).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</p>
                        <p class="w-full font-bold tracking-widest">₱ ${Number(u.tc).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</p>
                        
                    </div>
                `;

                table.append(row);
            });

            document.getElementById('tq_display').textContent = qtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('tc_display').textContent = "₱ " + ctotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        }
    })
    .catch(error => {console.error('Error Message!', error)})


}
setTimeout(function() {displaySupplyUsed();}, 1500);


//Close all popup
const cancelBtn = document.querySelectorAll('.close-popup');
cancelBtn.forEach(btn=>{
    btn.addEventListener('click', ()=>{
        addExpModal.classList.add('hidden');
        addTypeModal.classList.add('hidden');
    })
})