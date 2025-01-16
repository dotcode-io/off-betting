<div>
        <div class="flex justify-between">
            <div>
                <flux:heading size="xl" level="1">Good afternoon, Olivia</flux:heading>
                <flux:subheading size="lg" class="mb-6">Here's what's new today</flux:subheading>
               
            
            </div>
            <div>
            <flux:select  placeholder="Choose industry...">
                <flux:option>Photography</flux:option>
                <flux:option>Design services</flux:option>
                <flux:option>Web development</flux:option>
                <flux:option>Accounting</flux:option>
                <flux:option>Legal services</flux:option>
                <flux:option>Consulting</flux:option>
                <flux:option>Other</flux:option>
            </flux:select>
            </div>
        </div>
        <flux:separator variant="subtle" />
      <div class="flex">
        <div class="p-2 w-2/6">
            <flux:select  placeholder="Choose industry...">
                <flux:option>Photography</flux:option>
                <flux:option>Design services</flux:option>
                <flux:option>Web development</flux:option>
                <flux:option>Accounting</flux:option>
                <flux:option>Legal services</flux:option>
                <flux:option>Consulting</flux:option>
                <flux:option>Other</flux:option>
            </flux:select>
        </div>
        <div class="p-2 w-2/6">
            <flux:select  placeholder="Choose industry...">
                <flux:option>Photography</flux:option>
                <flux:option>Design services</flux:option>
                <flux:option>Web development</flux:option>
                <flux:option>Accounting</flux:option>
                <flux:option>Legal services</flux:option>
                <flux:option>Consulting</flux:option>
                <flux:option>Other</flux:option>
            </flux:select>
        </div>
      </div>
    <flux:table>
        <flux:columns>
            <flux:column>Customer</flux:column>
            <flux:column>Date</flux:column>
            <flux:column>Status</flux:column>
            <flux:column>Amount</flux:column>
        </flux:columns>
    
        <flux:rows>
            <flux:row>
                <flux:cell>Lindsey Aminoff</flux:cell>
                <flux:cell>Jul 29, 10:45 AM</flux:cell>
                <flux:cell><flux:badge color="green" size="sm" inset="top bottom">Paid</flux:badge></flux:cell>
                <flux:cell variant="strong">$49.00</flux:cell>
            </flux:row>
    
            <flux:row>
                <flux:cell>Hanna Lubin</flux:cell>
                <flux:cell>Jul 28, 2:15 PM</flux:cell>
                <flux:cell><flux:badge color="green" size="sm" inset="top bottom">Paid</flux:badge></flux:cell>
                <flux:cell variant="strong">$312.00</flux:cell>
            </flux:row>
    
            <flux:row>
                <flux:cell>Kianna Bushevi</flux:cell>
                <flux:cell>Jul 30, 4:05 PM</flux:cell>
                <flux:cell><flux:badge color="zinc" size="sm" inset="top bottom">Refunded</flux:badge></flux:cell>
                <flux:cell variant="strong">$132.00</flux:cell>
            </flux:row>
    
            <flux:row>
                <flux:cell>Gustavo Geidt</flux:cell>
                <flux:cell>Jul 27, 9:30 AM</flux:cell>
                <flux:cell><flux:badge color="green" size="sm" inset="top bottom">Paid</flux:badge></flux:cell>
                <flux:cell variant="strong">$31.00</flux:cell>
            </flux:row>
        </flux:rows>
    </flux:table>
</div>