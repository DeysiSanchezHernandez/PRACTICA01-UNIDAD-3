const BASE = document.getElementById('app').dataset.base;
const selCategoria = document.getElementById('categoria');
const inputBuscar  = document.getElementById('buscador');
const contFiltros  = document.getElementById('filtros');
const tbody        = document.getElementById('resultados');
const estado       = document.getElementById('estado');

let controladorBusqueda = null; // para cancelar peticiones obsoletas

// ---------- Filtros dinámicos por categoría ----------
async function cargarFiltros() {
    contFiltros.replaceChildren(); // limpia los filtros anteriores
    const id = selCategoria.value;

    if (id) {
        try {
            const res = await fetch(`${BASE}Productos/apiGetFiltros/${encodeURIComponent(id)}`);
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const data = await res.json();
            data.filtros.forEach(renderFiltro);
        } catch (err) {
            estado.textContent = 'No se pudieron cargar los filtros.';
            console.error(err);
            return;
        }
    }
    buscar();
}

function renderFiltro(f) {
    const label = document.createElement('label');
    label.textContent = f.nombre;

    const select = document.createElement('select');
    select.dataset.atributo = f.id;

    const todos = document.createElement('option');
    todos.value = '';
    todos.textContent = 'Todos';
    select.appendChild(todos);

    f.valores.forEach(v => {
        const op = document.createElement('option');
        op.value = v;
        op.textContent = v;
        select.appendChild(op);
    });

    select.addEventListener('change', buscar);
    label.appendChild(select);
    contFiltros.appendChild(label);
}

// ---------- Búsqueda en tiempo real ----------
async function buscar() {
    controladorBusqueda?.abort();
    controladorBusqueda = new AbortController();

    const filtros = {};
    contFiltros.querySelectorAll('select').forEach(s => {
        if (s.value) filtros[s.dataset.atributo] = s.value;
    });

    const params = new URLSearchParams({
        categoria_id: selCategoria.value || 0,
        q: inputBuscar.value.trim(),
        filtros: JSON.stringify(filtros),
    });

    try {
        estado.textContent = 'Buscando...';
        const res = await fetch(`${BASE}Productos/apiBuscar?${params}`, { signal: controladorBusqueda.signal });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const data = await res.json();
        renderTabla(data.productos);
        estado.textContent = `${data.productos.length} resultado(s)`;
    } catch (err) {
        if (err.name === 'AbortError') return; // petición reemplazada por otra más nueva
        estado.textContent = 'Error al buscar.';
        console.error(err);
    }
}

// textContent evita XSS (nunca innerHTML con datos del servidor)
function renderTabla(productos) {
    tbody.replaceChildren();
    productos.forEach(p => {
        const tr = document.createElement('tr');
        const tdNombre = document.createElement('td');
        const tdCat = document.createElement('td');
        const tdAttr = document.createElement('td');

        tdNombre.textContent = p.nombre;
        tdCat.textContent = p.categoria;
        tdAttr.textContent = p.atributos.map(a => `${a.nombre}: ${a.valor}`).join(' · ');

        tr.append(tdNombre, tdCat, tdAttr);
        tbody.appendChild(tr);
    });
}

// ---------- Debounce de 400 ms ----------
function debounce(fn, ms) {
    let t;
    return (...args) => {
        clearTimeout(t);
        t = setTimeout(() => fn(...args), ms);
    };
}

selCategoria.addEventListener('change', cargarFiltros);
inputBuscar.addEventListener('input', debounce(buscar, 400));
cargarFiltros(); // carga inicial
