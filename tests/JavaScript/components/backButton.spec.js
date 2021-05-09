import {
    shallowMount
} from '@vue/test-utils'
import BackButton from '@general/BackButton';

const wrapper = shallowMount(BackButton);
describe('BackButton', () => {
    it('has the text "Zurück"', () => {
        let buttonText = wrapper.find('a').text();
        expect(buttonText).to.equal("Zurück");
    });
});