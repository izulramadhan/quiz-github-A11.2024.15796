const registration = document.querySelector('[data-registration-form]');
if (registration) {
    const poli = registration.querySelector('#id_poli');
    const jadwal = registration.querySelector('#id_jadwal');
    const updateSchedules = () => {
        for (const option of jadwal.options) {
            if (!option.value) continue;
            option.hidden = option.dataset.poli !== poli.value;
            option.disabled = option.hidden;
        }
        if (jadwal.selectedOptions[0]?.disabled) jadwal.value = '';
    };
    poli.addEventListener('change', updateSchedules);
    updateSchedules();
}

const examination = document.querySelector('[data-examination-form]');
if (examination) {
    const picker = examination.querySelector('#pilih_obat');
    const hidden = examination.querySelector('#obat_json');
    const selected = examination.querySelector('[data-selected-medicines]');
    const medicines = new Map();
    const selectedIds = new Set();
    const money = amount => `Rp ${new Intl.NumberFormat('id-ID').format(amount)}`;
    for (const option of picker.options) {
        if (option.value) medicines.set(option.value, { name: option.dataset.name, price: Number(option.dataset.price) });
    }
    try {
        const restored = JSON.parse(hidden.value);
        if (Array.isArray(restored)) restored.forEach(id => { if (medicines.has(String(id))) selectedIds.add(String(id)); });
    } catch { /* A validation error can leave a malformed previous value. */ }
    const render = () => {
        selected.replaceChildren();
        let total = 0;
        if (!selectedIds.size) {
            const empty = document.createElement('p');
            empty.className = 'p-5 text-center text-sm text-slate-400';
            empty.textContent = 'Belum ada obat yang dipilih.';
            selected.append(empty);
        }
        for (const id of selectedIds) {
            const medicine = medicines.get(id);
            total += medicine.price;
            const row = document.createElement('div');
            row.className = 'flex items-center justify-between gap-3 border-b border-slate-100 px-4 py-3 last:border-0';
            const name = document.createElement('span');
            name.className = 'text-sm font-medium text-slate-700';
            name.textContent = medicine.name;
            const actions = document.createElement('div');
            actions.className = 'flex items-center gap-4';
            const price = document.createElement('span');
            price.className = 'text-sm text-slate-500';
            price.textContent = money(medicine.price);
            const remove = document.createElement('button');
            remove.type = 'button';
            remove.className = 'rounded px-2 py-1 text-sm text-red-500 hover:bg-red-50';
            remove.textContent = 'Hapus';
            remove.setAttribute('aria-label', `Hapus ${medicine.name}`);
            remove.addEventListener('click', () => { selectedIds.delete(id); render(); });
            actions.append(price, remove);
            row.append(name, actions);
            selected.append(row);
        }
        hidden.value = JSON.stringify([...selectedIds].map(Number));
        examination.querySelector('[data-medicine-total]').textContent = money(total);
        examination.querySelector('[data-examination-total]').textContent = money(total + Number(examination.querySelector('[data-service-fee]').dataset.serviceFee));
    };
    examination.querySelector('[data-add-medicine]').addEventListener('click', () => {
        if (!picker.value) { picker.focus(); return; }
        selectedIds.add(picker.value);
        picker.value = '';
        render();
    });
    render();
}

for (const form of document.querySelectorAll('form[data-confirm]')) {
    form.addEventListener('submit', event => {
        if (!window.confirm(form.dataset.confirm)) event.preventDefault();
    });
}
