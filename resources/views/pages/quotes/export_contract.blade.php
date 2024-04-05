<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ trans('quotes.title') }} #{{ $quote->quote_number }}v{{ $quote->version }} Contract</title>
 
    <link rel="stylesheet" href="pdf/plugins.bundle.css" type="text/css">
    <link rel="stylesheet" href="assets/css/style.bundle.css" type="text/css">
    <style>
        * {
            font-family: Arial!important;
        }
        body {
            margin: 40px 50px;
        }
        .p-t-80 {
            padding-top: 80px
        }
    </style>
</head>

<body>
    <div class="card flex-grow-1">
        <!--begin::Body-->
        <div class="card-body">
            <p>
                Location - Lease Agreement <br/>
                <br/>
                Between <br/>
                <br/>
                {{ $tenant->name }}<br/>
                <br/>
                - hereinafter also referred to as "Landlord" -<br/>
                <br/>
                and <br/>
                <br/>
                Coco and Jay <br/>
                <br/>
                - hereinafter also referred to as “Tenant” - <br/>
                <br/>
            </p>
            <div>
                <b class='title'>§1 Leased Property</b>
                <p>
                <br/>
                (1) The leased property is located on the premises {{($quote->eventArea ? $quote->eventArea->name : 'N/A')}}. <br/>
                <br/>
                (2) The total usable area of the leased property is approximately -----AreaSize
                </p>
                <br/>
            </div>
            <div>
                <b class='title'>§2 Purpose of Lease</b>
                <br/>
                <p>
                    <br/>
                    (1) The leased property is leased for the purpose see Annex 2. <br/>
                    <br/>
                    (2) Use of the leased premises for purposes other than those specified, as well as the expansion of the scope of services existing at the beginning of the contract, requires the prior written consent of the Landlord.<br/>
                    <br/>
                    (3) The Tenant confirms by signing that the space will not be used for any of the following purposes:<br/>
                    - Events that fulfill criminal offenses or are morally offensive;<br/>
                    <br/>
                    - Events with a background that is hostile to the constitution, especially with right- or left-wing extremist, racist, anti-Semitic, anti-Islamic, or anti-democratic content;<br/>
                    <br/>
                    - Events that involve derogation through racist discrimination or on the basis of gender, ethnic origin, religion or worldview, disability, age, or sexual identity.<br/>
                    Neither in word nor in writing may the freedom and dignity of individuals be scorned, nor may symbols representing or affiliated with the spirit of constitutional enemies or unconstitutional organizations be used or disseminated. The Tenant assures that the planned event does not contain any of the aforementioned content and commits to exclude participants who spread such content from the event.<br/>
                    <br/>
                    (4) The Landlord does not guarantee that the premises are suitable for the specific event planned. This applies especially with regard to possible regulatory or other restrictions that may contradict the Tenant's planning.<br/>
                </p>
                <br/>
            </div>
            <div>
                <b class='title'>§3 Commencement, Duration of Lease, and Termination</b>
                <p>
                    <br/>
                    (1) The lease begins on {{$dateFrom}} and ends on {{$dateTo}}. <br/>
                    <br/>
                    (2) The application of § 545 BGB, which deems the lease extended indefinitely if the Tenant continues to use the leased property after the lease period has ended, is excluded.<br/>
                </p>
                <br/>
            </div>
            <div>
                <b class='title'>§4 Handover</b>
                <p>
                    <br/>
                    (1) The handover takes place on {{$quote->buffer_time_before}}. <br/>
                    <br/>
                    (2) At the handover of the leased object to the Tenant, a joint protocol is to be prepared, noting any defects and the number of keys handed over.<br/>
                    <br/>
                    (3) The Tenant may only demand the handover of the leased object after having paid the agreed rent in advance – received into the account of the Landlord. If the handover is delayed due to late payment of the rent, this does not lead to a reduction in rent or to the cancellation of the Landlord's claim for payment.<br/>
                </p>
                <br/>
            </div>
            <div>
                <b class='title'>§5 Rent</b>
                <p>
                    <br/>
                    (1) The rent, including a service charge flat rate, amounts to: $ {{ number_format($quote->calculated_price, 2) }} plus statutory VAT.<br/>
                    <br/>
                    (2) This contract serves as an invoice in the sense of § 14 UStG in conjunction with section 14.1 paragraph 2 UStAE. Information required by VAT law not contained in this contract must be clearly, easily, and unambiguously ascertainable from other documents. In particular, the reason for payment and the billing period for which the payment is made must be clearly identifiable from the payment or bank documents.<br/>
                    <br/>
                    (3) In case of delay in rent payment, the Tenant is obliged to pay default interest at a rate of 9 percentage points above the respective base interest rate according to § 247 BGB. The Landlord is entitled to claim any further damages.<br/>
                </p>
                <br/>
            </div>
            <div>
                <b class='title'>§6 Payment</b>
                <p>
                    <br/>
                    The rent is to be paid after signing the contract, but no later than 14 days after invoicing, by transfer to the following account:<br/>
                    <br/>
                    Reinbeckhallen Betriebs GmbH IBAN: DE19 1012 0100 1004 0063 27 BIC: WELADED1WBB Purpose of use: Invoice number and offer number<br/>
                </p>
                <br/>
            </div>
            <div>
                <b class='title'>§7 Security Deposit</b>
                <p>
                    <br/>
                    The Tenant undertakes to provide a security deposit of €4,000.00 to the Landlord as security for all claims against them, especially for the payment of additional services, fulfillment of the obligation to restore the property to its original condition, and compensation for any damages that may arise. The security deposit is due with the rental invoice and must be transferred separately.<br/>
                </p>
                <br/>
            </div>
            <div>
                <b class='title'>§8 Set-off, Retention, and Rent Reduction</b>
                <p>
                    <br/>
                    (1) The Tenant is not entitled to offset claims from the Landlord arising from this contract with counterclaims, nor to assert a right of retention or a rent reduction, unless the counterclaim, the right of retention, or the rent reduction right is undisputed or has been legally established.<br/>
                    <br/>
                    (2) The Tenant's right to file a lawsuit to assert counterclaims and claims for rent reduction remains unaffected.<br/>
                </p>
                <br/>
            </div>
            <div>
                <b class='title'>§9 Subletting</b>
                <p>
                    <br/>
                    (1) Subletting or any other temporary transfer of use is not permitted.<br/>
                    <br/>
                    (2) The Landlord's permission to sublet can be revoked at any time for an important reason, i.e., if there are reasons related to the person or behavior of the subtenant that would entitle the Landlord to terminate the lease without notice if such reasons were present in the Tenant's person or behavior.<br/>
                    <br/>
                    (3) In the case of a transfer of use to third parties, the Tenant is liable for all actions or omissions of the subtenant, regardless of fault.<br/>
                </p>
                <br/>
            </div>
            <div>
                <b class='title'>§10 Duty of Care</b>
                <p>
                    <br/>
                    (1) The Tenant is responsible for the duty of care related to the leased property. The Tenant indemnifies the Landlord from all third-party claims resulting from a breach of their duty of care.<br/>
                    <br/>
                    (2) The Tenant is the organizer. They are obliged to take out event liability insurance at their own expense. The Tenant is responsible for complying with all applicable public and private regulations related to the event, especially the assembly places ordinance (VStättVO) of the state of Berlin and the youth protection act.<br/>
                </p>
                <br/>
            </div>
            <div>
                <b class='title'>§11 Permits</b>
                <p>
                    <br/>
                    The Tenant is responsible, at their own expense, for creating the actual and legal conditions for the operation of their business on the leased property, including obtaining all necessary official and possibly further permits. All official requirements or demands related to the operation and use of the leased property are to be fulfilled by the Tenant at their own expense. The Landlord is not liable for the granting or renewal of necessary official permits for the operation, as far as they lie within the Tenant's sphere. The Landlord is not liable for the absence or loss of commercial legal requirements for the person or the business of the Tenant. A change in the use of the property is only permissible with the prior written consent of the Landlord.<br/>
                </p>
                <br/>
            </div>
            <div>
                <b class='title'>§12 Landlord's Liability</b>
                <p>
                    <br/>
                    (1) Claims for damages due to defects in the leased property are only available to the Tenant if the Landlord is responsible for the defect intentionally or due to gross negligence or if the Landlord is intentionally or grossly negligently delayed in remedying the defect.<br/>
                    <br/>
                    (2) The Landlord is not liable for damages to the Tenant's goods and fixtures, regardless of the nature, origin, duration, and extent of the effects, unless the Landlord has caused the damage intentionally or through gross negligence.<br/>
                    <br/>
                    (3) The Landlord ensures a proper connection of the leased property to the supply facilities but is not liable for damages resulting from this, especially due to disruption and interruption, unless these damages are due to intentional or grossly negligent actions or omissions of the Landlord. The Landlord is also not liable for damages caused by waste or changes in electrical voltage; the Tenant must protect themselves with suitable technical devices.<br/>
                    <br/>
                    (4) The liability of the Landlord according to § 536a paragraph 1 Alt. 1 BGB, according to which the Landlord is liable for defects that existed at the time of the conclusion of the contract even if he is not at fault, is excluded, unless the Landlord acted intentionally or with gross negligence.<br/>
                    <br/>
                    (5) The restriction and exclusion of the Landlord's liability according to the preceding provisions do not apply to damages from injury to life, body, or health. In this respect, the Landlord is fully liable for intent and negligence.<br/>
                </p>
                <br/>
            </div>
            <div>
                <b class='title'>§13 Tenant's Liability</b>
                <p>
                    <br/>
                    (1) The Tenant must treat the leased property with care, keep it clean, and free from pests and rodents.<br/>
                    <br/>
                    (2) Damages to the rented rooms, the building, and the property, as well as to the facilities and installations belonging to the building or property, must be reported to the Landlord immediately, i.e., no later than the next calendar day after becoming aware of them. The Tenant is liable for further damages caused by delayed notification.<br/>
                    <br/>
                    (3) The Tenant is responsible for any damage within the leased property, even if the damage is caused by their agents, subtenants, customers, visitors, suppliers, craftsmen, or other persons allowed by the Tenant to be in the leased property.<br/>
                    <br/>
                    (4) Damages to the rented rooms, the building, and the property, as well as to the facilities and installations belonging to the building or property, caused by the breach of the Tenant's duty of care, must be remedied by the Tenant at their own expense. The Tenant is similarly liable for damages caused by their agents, subtenants, customers, visitors, suppliers, craftsmen, or other persons allowed by the Tenant to be in the leased property.<br/>
                    <br/>
                    (5) Before installing heavy equipment, the Tenant must inquire with the Landlord about the permissible load on the floor slabs. The permissible load must not be exceeded. If it is exceeded, the Tenant is liable for all resulting damages and consequential damages and must indemnify the Landlord from any third-party claims arising from this.<br/>
                    <br/>
                    (6) All waste, especially commercial waste and large packaging, as well as other bulky trash, must be disposed of by the Tenant themselves.<br/>
                </p>
                <br/>
            </div>
            <div>
                <b class='title'>§14 Access to the Leased Property</b>
                <p>
                    <br/>
                    The Landlord or their agents and authorized representatives are allowed to enter the leased property, especially to verify the contractual use and to terminate the event in case of significant breaches of this contract. In case of emergencies, the Landlord, their agents, and accompanying third parties must be granted access at any time.<br/>
                </p>
                <br/>
            </div>
            <div>
                <b class='title'>§15 Event Technology and Furniture</b>
                <p>
                    <br/>
                    The Tenant is obliged to rent the beverage gastronomy, event technology, and furniture from a partner named by the Landlord. The partners are as follows: Event technology: Lautwerfer Veranstaltungstechnik GmbH Event equipment: PHOENIX Entertainment Veranstaltungs GmbH Beverage gastronomy: Riedel and Nguyen GbR<br/>
                </p>
                <br/>
            </div>
            <div>
                <b class='title'>§16 Termination of the Lease</b>
                <p>
                    <br/>
                    Upon termination of the lease, the Tenant is obliged to return the leased property in faultless (original) condition to the Landlord.<br/>
                </p>
                <br/>
            </div>
            <div>
                <b class='title'>§17 Joint and Several Liability, Authorization to Receive</b>
                <p>
                    <br/>
                    (1) If several natural or legal persons are Tenants, they are jointly and severally liable for fulfilling the obligations arising from this contract.<br/>
                    <br/>
                    (2) Several persons as Tenants authorize each other to receive all declarations concerning the lease. This authorization is granted with exemption from the restrictions of § 181 BGB. For the legal effectiveness of a declaration by the Landlord, it is sufficient if it is made to one of the Tenants.<br/>
                </p>
                <br/>
            </div>
            <div>
                <b class='title'>§18 Consent Declarations of the Landlord</b>
                <p>
                    <br/>
                    Any consent declarations by the Landlord are always granted, even if this is not expressly stated in the consent declaration, subject to any necessary official permission.<br/>
                </p>
                <br/>
            </div>
            <div>
                <b class='title'>§19 Written Form</b>
                <p>
                    <br/>
                    All agreements made between the parties are contained in this contract. Previous written or oral lease agreements are nullified by the effectiveness of this contract. Subsequent changes and additions to this contract require written form. This also applies to a change/cancellation of the written form requirement. The parties are aware of the special legal written form requirements of §§ 126, 550, 578 BGB. They hereby mutually oblige themselves to perform all actions and make all declarations necessary at any time upon request by one party to meet the legal written form requirement and not to terminate the lease prematurely on the grounds of non-compliance with the legal written form. This not only applies to the conclusion of the original contract but also to supplementary, amendment, and supplementary contracts.<br/>
                </p>
                <br/>
            </div>
            <div>
                <b class='title'>§20 Severability Clause</b>
                <p>
                    <br/>
                    Should one or more provisions of this contract be or become invalid for any reason, or should there be any gaps in this contract, this shall not affect the validity of the rest of the lease. The contracting parties commit to replacing the invalid provision with a valid one that most closely approximates the purpose of the omitted provision.<br/>
                </p>
                <br/>
            </div>
            <div>
                <b class='title'>§21 Other Agreements</b>
                <p>
                    <br/>
                    If the invalidity of the contract arises from a legally impermissible measure of performance or time (deadline or date) contained therein, a measure of performance or time (deadline or date) that comes as close as possible to this, but is legally permissible, shall be deemed agreed upon from the outset.<br/>
                </p>
                <br/>
            </div>
            <div class='container-fluid p-t-80'>
                <p id="datePlaceholder">Berlin, dated {{$today}}</p>
                <table class='table'>
                    <tbody>
                        <tr style='container-fluid'>
                            <td>{{ $tenant->name }}</td>
                            <td class='text-end' >Coco and Jay</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>