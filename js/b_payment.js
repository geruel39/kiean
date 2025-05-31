const addPaymentModal = document.getElementById('add_payment_modal');

function displayPaymentRecords(){
    const search = document.getElementById('payment_search');
    const year = document.getElementById('payment_year');
    const month = document.getElementById('payment_month');

    search.onsearch = ()=>{
        displayPaymentRecords();
    }
    year.onchange = ()=>{
        displayPaymentRecords();
    }
    month.onchange = ()=>{
        displayPaymentRecords();
    }

    const info = {
        displayPaymentRecords: true,
        search: search.value,
        year: year.value,
        month: month.value,
        branch: sessionStorage.getItem('branch')
    }

    const table = document.getElementById('payment_record_table');
    table.innerHTML = "";

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Payment Records: ",data);

        if(!data.result){
            table.innerHTML = `
                    <div class="flex p-2 my-1 rounded border border-blue-500">
                        <p class="w-full text-sm font-bold text-center">No Result...</p>
                    </div>
            `
        }else{
            data.records.forEach(r=>{

                let status = "";
                let color = "";

                switch (true) {
                    case (r.paid == 0):
                        status = "No Payment";
                        color = "text-gray-500";
                        break;
                    case (r.status > 0):
                        status = "Partial Payment";
                        color = "text-blue-500";
                        break;
                    case (r.status < 0):
                        status = "Overpay";
                        color = "text-red-500";
                        break;
                    default:
                        status = "Fully Paid";
                        color = "text-green-500";
                        break;
                }


                const row = document.createElement('div');
                row.innerHTML = `

                    <div class="flex items-center p-2 my-1 rounded border border-blue-500">
                        <p class="w-full text-sm font-bold">${r.fname} ${r.mname} ${r.lname}</p>
                        <p class="w-full text-sm font-bold">${r.rates}</p>
                        <p class="w-full text-sm font-bold">${r.paid}</p>
                        <p class="w-full text-sm ${color} font-bold">${status}</p>
                        <div class="w-full flex justify-end space-x-3">
                            <button class="p-2 text-xs bg-yellow-500 rounded opacity-80 hover:opacity-100 payment" data-id='${r.id}'>Payment</button>
                        </div>
                    </div>
                `

                table.append(row);
            })

            const payment = document.querySelectorAll('.payment');
            payment.forEach(p=>{
                p.addEventListener('click', e=>{
                    const id = e.currentTarget.getAttribute('data-id');

                    document.getElementById('confirm_payment').setAttribute('data-id', id)

                    document.getElementById('date_period').textContent = "Date Period: " + month.value + "-" + year.value;

                    addPaymentModal.classList.remove('hidden');
                })
            })
        }
    })
    .catch(error => {console.error('Error Message!', error)})
}
setTimeout(function() {displayPaymentRecords();}, 1500);

//Add Payment
const confirmPayment = document.getElementById('confirm_payment');
confirmPayment.onclick = ()=>{

    const amount = document.getElementById('payment_amount');

    const info = {
        addPayment: true,
        id: confirmPayment.getAttribute('data-id'),
        amount: amount.value
    }

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Payment: ",data);

        if(!data.result){
            alert(data.message);
        }else{
            addPaymentModal.classList.add('hidden');
            amount.value = "";
            displayPaymentRecords();
        }
    })
    .catch(error => {console.error('Error Message!', error)})
}

function displayPaymentHistory(){
    const info = {
        displayPaymentHistory: true,
        branch: sessionStorage.getItem('branch')
    }

    const table = document.getElementById('payment_history_table');
    table.innerHTML = "";

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Payment History: ",data);

        if(!data.result){
            table.innerHTML = `
                    <div class="flex p-2 my-1 rounded border border-blue-500">
                        <p class="w-full text-sm font-bold text-center">No Result...</p>
                    </div>
            `;
        }else{
            data.records.forEach(r=>{
                const btn = r.via == 'Manual' ? `<button class='w-full p-2 text-xs text-red-500 hover:underline del_pay' data-id='${r.payment_id}'>Delete</button>` : "<p class='w-full'></p>";

                const row = document.createElement('div');
                row.innerHTML = `
                    <div class="flex items-center p-2 my-1 rounded border border-blue-500 cursor-pointer hover:bg-gray-300">
                        <p class="w-full text-sm font-bold">${r.tutee}</p>
                        <p class="w-full text-sm font-bold">${r.amount}</p>
                        <p class="w-full text-sm font-bold">${r.tp}</p>
                        <p class="w-full text-sm font-bold">${r.date}</p>
                        ${btn}
                    </div>
                `;

                table.append(row);
            })

            document.querySelectorAll('.del_pay').forEach(d=>{
                d.addEventListener('click', e=>{
                    const id = e.currentTarget.getAttribute('data-id');

                    let confirm = window.confirm('Do you want to delete this payment record?');
                    if(!confirm){
                        return;
                    }

                    const info = {
                        deletePayment: true,
                        id: id
                    }

                    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
                    .then(response => response.json())
                    .then(data =>{
                        console.log( "Delete Payment: ",data);

                        if(!data){
                            alert('Something went wrong.');
                        }else{
                            displayPaymentHistory();
                            displayPaymentRecords();
                        }
                    })
                    .catch(error => {console.error('Error Message!', error)})
                })
            })

        }
    })
    .catch(error => {console.error('Error Message!', error)})

}
setTimeout(function() {displayPaymentHistory();}, 1500);


//Close all popup
const cancelBtn = document.querySelectorAll('.close-popup');
cancelBtn.forEach(btn=>{
    btn.addEventListener('click', ()=>{
        addPaymentModal.classList.add('hidden');
    })
})