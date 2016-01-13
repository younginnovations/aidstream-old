<?php

class Iati_Aidstream_Form_Activity_Transaction_Sector extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $model = new Model_Wep();

        $this->setAttrib('class', 'first-child')
            ->setMethod('post')
            ->setIsArray(true);

        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);

        $sectorVocabulary = $model->getCodeArray('SectorVocabulary', null, '1', true);
        $form['vocabulary'] = new Zend_Form_Element_Select('vocabulary');
        $form['vocabulary']->setLabel('Sector Vocabulary')
            ->setValue($this->data['@vocabulary'])
            ->setAttrib('class', 'form-select vocabulary_value')
            ->setMultioptions($sectorVocabulary);

        $sector = $model->getCodeArray('Sector', null, '1', true);
        $form['code'] = new Zend_Form_Element_Select('code');
        $form['code']->setLabel('Sector')->setRequired();
        if ($this->data['@vocabulary'] == 3) {
            $form['code']->setValue($this->data['@code']);
        }
        $form['code']->setAttrib('class', 'form-select sector_value')
            ->setMultioptions($sector);

        $sector_dac_three = $model->getCodeArray('SectorDacThree', null, '1', true);
        $form['dac_three_code'] = new Zend_Form_Element_Select('dac_three_code');
        $form['dac_three_code']->setLabel('Sector')->setRequired();
        if ($this->data['@vocabulary'] == 8) {
            $form['dac_three_code']->setValue($this->data['@code']);
        }
        $form['dac_three_code']->setAttrib('class', 'form-select dac_three_code')
            ->setMultioptions($sector_dac_three);

        $form['non_dac_code'] = new Zend_Form_Element_Text('non_dac_code');
        $form['non_dac_code']->setLabel('Sector')
            ->setRequired()
            ->setValue($this->data['@code'])
            ->setAttrib('class', 'form-text non_dac_code');

        $this->addElements($form);
        return $this;
    }
}
