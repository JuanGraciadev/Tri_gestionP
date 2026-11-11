/**
 * Script de página: productos.js
 */
function openModal(id) {
            const modal   = document.getElementById(id);
            const content = document.getElementById(id + 'Content');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            requestAnimationFrame(() => {
                modal.style.opacity = '1';
                if (content) content.style.transform = 'scale(1)';
            });
        }

        function closeModalAnim(modalId, contentId) {
            const modal   = document.getElementById(modalId);
            const content = document.getElementById(contentId);
            modal.style.opacity = '0';
            if (content) content.style.transform = 'scale(0.95)';
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                modal.style.opacity = '';
                if (content) content.style.transform = '';
            }, 300);
        }

        function openEditModal(producto) {
            const updateUrl = (window.APP.rutas.updateProducto || "").replace(":id", producto.id_producto);
            document.getElementById('formEditar').action = updateUrl;
            document.getElementById('edit_nombre').value = producto.nombre;
            document.getElementById('edit_precio').value = producto.precio;
            document.getElementById('edit_id_categoria').value = producto.id_categoria;
            document.getElementById('edit_img_actual').value = producto.img || '';
            document.getElementById('edit_retornable').checked = (producto.retornable == 1 || producto.retornable === true);
            document.getElementById('editProductoSubtitle').textContent = 'Editando: ' + producto.nombre;

            const zone    = document.getElementById('editUploadZone');
            const preview = document.getElementById('editPreview');
            if (producto.img) {
                preview.src = (window.APP.assetUrl || "") + producto.img;
                zone.classList.add('has-image');
            } else {
                preview.src = '';
                zone.classList.remove('has-image');
            }
            document.getElementById('edit_img_file').value = '';
            openModal('modalEditar');
        }

        function confirmDeleteProduct(idProducto) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción eliminará el producto permanentemente.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="fas fa-trash mr-2"></i>Sí, eliminar',
                cancelButtonText: 'Cancelar',
                customClass: { popup: 'rounded-[2rem] font-outfit' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteProductForm' + idProducto).submit();
                }
            });
        }

        // ── Upload zone handlers ──
        function handleFileSelect(input, zoneId, previewId) {
            const zone = document.getElementById(zoneId);
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const file = input.files[0];
                if (file.size > 5 * 1024 * 1024) {
                    Swal.fire({ 
                        icon: 'warning', 
                        title: 'Archivo muy grande', 
                        text: 'La imagen no debe superar 5MB.', 
                        confirmButtonColor: '#0ea5e9', 
                        customClass: { popup: 'rounded-[2rem] font-outfit' } 
                    });
                    input.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    zone.classList.add('has-image');
                };
                reader.readAsDataURL(file);
            }
        }

        function removeImage(zoneId, inputId, previewId) {
            const zone = document.getElementById(zoneId);
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            input.value = '';
            preview.src = '';
            zone.classList.remove('has-image');
        }

        // Drag & drop support
        document.querySelectorAll('.upload-zone').forEach(zone => {
            zone.addEventListener('dragover', e => {
                e.preventDefault();
                zone.classList.add('drag-over');
            });
            zone.addEventListener('dragleave', e => {
                e.preventDefault();
                zone.classList.remove('drag-over');
            });
            zone.addEventListener('drop', e => {
                e.preventDefault();
                zone.classList.remove('drag-over');
                const fileInput = zone.querySelector('input[type="file"]');
                if (e.dataTransfer.files.length > 0) {
                    fileInput.files = e.dataTransfer.files;
                    const event = new Event('change', { bubbles: true });
                    fileInput.dispatchEvent(event);
                }
            });
        });

        // Close modal on outside click
        window.addEventListener('click', function(e) {
            ['modalCrear','modalEditar'].forEach(id => {
                if (e.target.id === id) closeModalAnim(id, id + 'Content');
            });
        });

        // Real-time Search
        document.getElementById('searchProductos').addEventListener('input', function() {
            const term = this.value.toLowerCase();
            document.querySelectorAll('.producto-card').forEach(card => {
                card.style.display = card.dataset.nombre.includes(term) ? '' : 'none';
            });
        });
