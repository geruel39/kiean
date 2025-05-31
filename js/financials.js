function getMonthName(monthNumber) {
    const monthNames = [
      'January', 'February', 'March', 'April', 'May', 'June',
      'July', 'August', 'September', 'October', 'November', 'December'
    ];
  
    const monthIndex = parseInt(monthNumber, 10) - 1;
  
    return monthNames[monthIndex] || 'Invalid month';
}


document.getElementById('o_year').onchange = ()=>{displayOverviewTotal();}
document.getElementById('o_month').onchange = ()=>{displayOverviewTotal();}
document.getElementById('r_year').onchange = ()=>{displayFinancialRecords();}
document.getElementById('r_month').onchange = ()=>{displayFinancialRecords();}

document.getElementById('o_date').textContent = getMonthName(document.getElementById('o_month').value) + " " + document.getElementById('o_year').value;
document.getElementById('r_date').textContent = getMonthName(document.getElementById('r_month').value) + " " + document.getElementById('r_year').value;

const displayOverviewTotal = () => {

    const info = {
        displayOverviewTotal: true,
        year: document.getElementById('o_year').value,
        month: document.getElementById('o_month').value
    }

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Overview: ",data);

        let income = Number(data.payments) - (Number(data.expenses) + Number(data.supply));

        document.getElementById('payment').textContent = Number(data.payments).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('expenses').textContent = Number(data.expenses).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('supply').textContent = Number(data.supply).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('income').textContent = income.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    })
    .catch(error => {console.error('Error Message!', error)})
}
displayOverviewTotal();

const displayFinancialRecords = () => {
    const info = {
        displayFinancialRecords: true,
        year: document.getElementById('r_year').value,
        month: document.getElementById('r_month').value
    }

    const table = document.getElementById('records_table');
    table.innerHTML = "";

    fetch('z_process.php', {method: 'POST',headers: {'Content-Type': 'application/json'},body: JSON.stringify(info)})
    .then(response => response.json())
    .then(data =>{
        console.log( "Financial Records: ",data);

        if(!data.result){
            table.innerHTML = `
                    <div class="flex p-2 my-1 rounded border border-red-500 hover:bg-gray-300">
                        <p class="w-full font-bold text-center">No Results</p>
                    </div>
                `;
        }else{
            let totalp = 0;
            let totale = 0;
            let totals = 0;
            let totali = 0;
            let totalc = 0;

            data.branches.forEach(b=>{

                const row = document.createElement('div');
                row.innerHTML = `
                    <div class="flex p-2 my-1 rounded border border-red-500 hover:bg-gray-300">
                        <p class="w-full font-bold text-sm">${b.name}</p>
                        <p class="w-full font-bold text-sm">${Number(b.sales).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</p>
                        <p class="w-full font-bold text-sm">${Number(b.expenses).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</p>
                        <p class="w-full font-bold text-sm">${Number(b.supply).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</p>
                        <p class="w-full font-bold text-sm">${Number(b.income).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</p>
                        <p class="w-full font-bold text-sm">${Number(b.commission).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</p>
                    </div>
                `;

                table.append(row);

                totalp += Number(b.sales);
                totale += Number(b.expenses);
                totals += Number(b.supply);
                totali += Number(b.income);
                totalc += Number(b.commission); 
            });

            document.getElementById('t_p').textContent = totalp.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('t_e').textContent = totale.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('t_s').textContent = totals.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('t_i').textContent = totali.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('t_c').textContent = totalc.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    })
    .catch(error => {console.error('Error Message!', error)})
}
displayFinancialRecords();