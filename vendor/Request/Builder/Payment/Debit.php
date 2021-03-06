<?php
/**
 * PAYONE Prestashop Connector is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PAYONE Prestashop Connector is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with PAYONE Prestashop Connector. If not, see <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 *
 * @author    patworx multimedia GmbH <service@patworx.de>
 * @copyright 2003 - 2018 BS PAYONE GmbH
 * @license   <http://www.gnu.org/licenses/> GNU Lesser General Public License
 * @link      http://www.payone.de
 */

namespace Payone\Request\Builder\Payment;

class Debit extends Base
{

    /**
     * Builds payment request
     */
    public function build()
    {
        parent::build();
        $this->setParam('narrative_text', $this->getPayment()->getTitle());
        $this->setUserToRequest();
        $this->setPaymentDataToRequest();
    }

    /**
     * Sets debit payment data to request
     */
    public function setPaymentDataToRequest()
    {
        $aPaymentInfo = $this->getForm();
        if ($aPaymentInfo['bankdatatype'] == 1) {
            $this->setParam('iban', $aPaymentInfo['iban']);
            $this->setParam('bic', isset($aPaymentInfo['bic']) ? $aPaymentInfo['bic'] : '');
        } else {
            $this->setParam('bankaccount', $aPaymentInfo['bankaccount']);
            $this->setParam('bankcode', $aPaymentInfo['bankcode']);
        }
        $this->setParam('bankcountry', $aPaymentInfo['bankcountry']);
        $this->setParam('bankaccountholder', false);# $aPaymentInfo['bankaccountholder']
        //$this->setParam('bankaccountholder',$this->getParam('firstname').' '.$this->getParam('lastname'));

        if (isset(\Context::getContext()->cookie->sFcPayoneMandate)) {
            $aMandate = \Tools::jsonDecode(\Context::getContext()->cookie->sFcPayoneMandate, true);
            $this->setParam('mandate_identification', $aMandate['mandate_identification']);
        }
    }
}
