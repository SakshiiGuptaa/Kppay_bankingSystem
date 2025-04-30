<div class="modal fade" id="addBox" tabindex="-1" aria-labelledby="addBoxModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content site-table-modal">
            <div class="modal-body popup-body">
                <button type="button" class="modal-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-lucide="x"></i>
                </button>
                <div class="popup-body-text">
                    <div class="title">{{ __('Create feature') }}</div>
                    <form action="{{ route('user.merchant_account.feature.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                    <div class="step-details-form">
                        <div class="row">
                        <!-- Feature Name -->
                        
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <div class="inputs">
                                <label for="feature_name" class="input-label d-block">{{ __('Feature Name') }}<span class="required">*</span></label>
                                <div style="font-size:13px;">This won't be shown to customers.</div>
                                <input type="text" name="feature_name" id="feature_name" class="box-input" placeholder="Enter Feature Name" required>
                            </div>
                        </div>
                        
                        <!-- Lookup Name -->
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <div class="inputs">
                                <label for="lookup_key" class="input-label d-block">{{ __('Lookup Key') }}</label>
                                <div style="font-size:13px;">A unique key you provide as your own identifier to support easier retrieval.</div>
                                <input type="text" name="lookup_key" id="lookup_key" class="box-input" placeholder="Enter Lookup Key" required>
                            </div>
                        </div>
                        
                    
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <div class="inputs">
                                <label for="metadata_section" class="input-label d-block">{{ __('Metadata') }}</label>
                                <div style="font-size:13px;">Store additional, structured information on the feature.</div>
                                <button type="button" id="add-metadata" class="site-btn-sm primary-btn" style="margin-bottom: 12px;">
                                    {{ __('Add Metadata') }}
                                </button>
                            </div>
                            <div id="metadata-fields">
                                <!-- Dynamic metadata fields will be appended here -->
                            </div>
                        </div>
                    </div>
                </div>

                        <!-- Action Buttons -->
                        <div class="action-btns mt-3">
                            <button type="submit" class="site-btn-sm primary-btn me-2">
                                <i data-lucide="check"></i> {{ __('Create feature') }}
                            </button>
                            <button type="button" class="site-btn-sm red-btn" data-bs-dismiss="modal" aria-label="Close">
                                <i data-lucide="x"></i> {{ __('Cancel') }}
                            </button>
                        </div>
                        
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    // Add Metadata Field (Key-Value Pair)
    document.getElementById('add-metadata').addEventListener('click', function () {
        const container = document.getElementById('metadata-fields');

        // Create a new div for metadata fields
        const metadataDiv = document.createElement('div');
        metadataDiv.className = 'metadata-pair d-flex align-items-center mb-2';

        // Create Key input field
        const keyInput = document.createElement('input');
        keyInput.type = 'text';
        keyInput.name = 'metadata_keys[]';
        keyInput.className = 'box-input me-2';
        keyInput.placeholder = 'Key';
        keyInput.required = true;

        // Create Value input field
        const valueInput = document.createElement('input');
        valueInput.type = 'text';
        valueInput.name = 'metadata_values[]';
        valueInput.className = 'box-input me-2';
        valueInput.placeholder = 'Value';
        valueInput.required = true;

        // Create Remove button
        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.className = 'site-btn-sm red-btn';
        removeButton.innerText = 'Remove';
        removeButton.addEventListener('click', function () {
            metadataDiv.remove();
        });

        // Append Key, Value fields and Remove button to metadataDiv
        metadataDiv.appendChild(keyInput);
        metadataDiv.appendChild(valueInput);
        metadataDiv.appendChild(removeButton);

        // Append metadataDiv to container
        container.appendChild(metadataDiv);
    });

</script>