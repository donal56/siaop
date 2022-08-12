<?php
    namespace app\components\CustomGrid;

    use yii\grid\DataColumn;
    use yii\helpers\ArrayHelper;
    use yii\web\View;

    class CustomEditableColumn extends DataColumn {

        public $inputType = 'text';

        public function init() {

            $js = <<<JS

            function editCell(event) {
                
                if(this.tagName == 'TD' && event.target.childElementCount == 0) {
                    const value = this.textContent;
                    this.textContent = '';
                    
                    const container = document.createElement('DIV');
                    container.className = "form-group-sm";
                    container.style = "width: 100%";
                    this.appendChild(container);

                    const input = document.createElement('INPUT');
                    input.type = '$this->inputType';
                    input.value = value;
                    input.className = 'form-control';
                    container.appendChild(input);
                    
                    const hint = document.createElement('DIV');
                    hint.style.backgroundColor = '#e8e8e8';
                    hint.style.border = '1px solid #c5c5c5';
                    hint.style.borderRadius = '5px';
                    hint.style.fontSize = '12px';
                    hint.style.color = 'black';
                    hint.style.margin = '2px';
                    hint.style.padding = '2px';
                    hint.style.position = 'absolute';
                    hint.style.width = '150px';
                    hint.innerHTML = 'Presione <kbd>↵</kbd> (Enter) para confirmar.';
                    container.appendChild(hint);

                    input.focus();
                    input.select();
                    input.addEventListener('keypress', finishCellEditing);
                    input.addEventListener('blur', cancelCellEditing);
                }
            }

            function finishCellEditing(event) {

                if(this.tagName == 'INPUT') {

                    if(event.keyCode == 13) {

                        const value = this.value;
                        const td = this.parentElement.parentElement;
                        const attr = td.getAttribute("data-attribute");
    
                        const mainTd = td.parentElement.querySelector("td[data-json]");
                        const json = JSON.parse(mainTd.getAttribute("data-json"));

                        td.innerHTML = '';
                        td.textContent = value;
                        
                        setbyPath(json, attr, value);
                        mainTd.setAttribute("data-json", JSON.stringify(json));
                    }
                }
            }

            function cancelCellEditing(event) {
                if(this.tagName == 'INPUT') {
                    const td = this.parentElement.parentElement;
                    const attr = td.getAttribute("data-attribute");

                    const mainTd = td.parentElement.querySelector("td[data-json]");
                    const json = JSON.parse(mainTd.getAttribute("data-json"));

                    td.innerHTML = getbyPath(json, attr);
                }
            }
JS;
            $this->grid->view->registerJs($js, View::POS_END);

            $js = <<<JS
            
            $(function() {
                document.querySelectorAll('td[data-editable]').forEach(function(el) {
                    el.addEventListener('dblclick', editCell);
                })
            });
JS;
            $this->grid->addAfterScript($js, "CustomEditableColumn", true);

            $this->contentOptions = ArrayHelper::merge($this->contentOptions, [
                'data-editable' => "$this->inputType",
                'data-attribute' => "$this->attribute"
            ]);

            parent::init();
        }
    }