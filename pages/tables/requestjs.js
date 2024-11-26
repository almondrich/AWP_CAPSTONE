<script>document.getElementById('addItemBtn').addEventListener('click', function () {
        const itemContainer = document.getElementById('itemContainer');
        const newItemEntry = document.createElement('div');
        newItemEntry.classList.add('card-item');
        newItemEntry.innerHTML = `
            <button type="button" class="close-btn" onclick="removeItem(this)">×</button>
            <div class="item-details">
                <label for="item_id">Item:</label>
                <select name="item_id[]" class="form-control item-select" required onchange="updateDetails(this)">
                    <option value="" disabled selected>Select an item</option>
                    <?php
                    $result = $con->query("SELECT items.item_id, items.item_name, items.item_desc, items.qty, units.unit_id, units.unit_desc 
                                           FROM items 
                                           JOIN units ON items.unit_id = units.unit_id");
                    if ($result) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['item_id']}' 
                                   data-description='{$row['item_desc']}' 
                                   data-stock='{$row['qty']}' 
                                   data-unit-id='{$row['unit_id']}' 
                                   data-unit-desc='{$row['unit_desc']}'>{$row['item_name']}</option>";
                        }
                    }
                    ?>
                </select>
                <p class="item-description" style="font-size: 14px; color: #555;">Description: <span>Not selected</span></p>
                <p class="item-stock" style="font-size: 14px; color: #555;">Available Stock: <span>Not selected</span></p>
            </div>
            <div class="unit-container">
                <label for="unit_id">Unit:</label>
                <select name="unit_id[]" class="form-control unit-select" required>
                    <option value="" disabled selected>Select a unit</option>
                    <?php
                    $unitsResult = $con->query("SELECT unit_id, unit_desc FROM units");
                    if ($unitsResult) {
                        while ($unitRow = $unitsResult->fetch_assoc()) {
                            echo "<option value='{$unitRow['unit_id']}'>{$unitRow['unit_desc']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="quantity-container">
                <label class="quantity-label">Quantity</label>
                <div class="quantity-control">
                    <button type="button" class="btn btn-secondary decrement-btn">-</button>
                    <input type="number" name="quantity[]" class="form-control quantity-input" value="1" min="1" required>
                    <button type="button" class="btn btn-secondary increment-btn">+</button>
                </div>
            </div>`;
        itemContainer.appendChild(newItemEntry);
        attachQuantityHandlers(newItemEntry);
        preventDuplicateSelection();
    });

    function attachQuantityHandlers(item) {
        const decrementBtn = item.querySelector('.decrement-btn');
        const incrementBtn = item.querySelector('.increment-btn');
        const quantityInput = item.querySelector('.quantity-input');

        decrementBtn.addEventListener('click', () => {
            if (quantityInput.value > 1) {
                quantityInput.value--;
            }
        });

        incrementBtn.addEventListener('click', () => {
            quantityInput.value++;
        });
    }

    function updateDetails(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const description = selectedOption.getAttribute('data-description') || 'No description available.';
        const stock = selectedOption.getAttribute('data-stock') || 'Not selected';

        const itemDetails = selectElement.closest('.item-details');
        itemDetails.querySelector('.item-description span').textContent = description;
        itemDetails.querySelector('.item-stock span').textContent = stock;

        preventDuplicateSelection();
    }

    function preventDuplicateSelection() {
        const allSelectedItems = Array.from(document.querySelectorAll('.item-select')).map(select => select.value);
        const allOptions = document.querySelectorAll('.item-select option');

        allOptions.forEach(option => {
            if (allSelectedItems.includes(option.value) && option.value !== '') {
                option.disabled = true;
            } else {
                option.disabled = false;
            }
        });
    }

    function removeItem(button) {
        const cardItem = button.closest('.card-item');
        cardItem.remove();
        preventDuplicateSelection();
    }

    document.querySelectorAll('.card-item').forEach(item => {
        attachQuantityHandlers(item);
        preventDuplicateSelection();
    });


</script>
    